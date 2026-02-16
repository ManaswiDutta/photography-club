import os
import subprocess
import json
import re
import sys
import requests
from datetime import datetime
from concurrent.futures import ThreadPoolExecutor

# Configuration
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
UPLOADS_DIR = os.path.abspath(os.path.join(BASE_DIR, "../uploads"))
INGEST_SCRIPT = os.path.join(BASE_DIR, "import_db.php")
TEMP_DIR = os.path.join(BASE_DIR, "../temp")

if not os.path.exists(TEMP_DIR):
    os.makedirs(TEMP_DIR)

# Local PHP binary path
LOCAL_PHP = "php"
if os.path.exists("C:\\xampp_feb26\\php\\php.exe"):
    LOCAL_PHP = "C:\\xampp_feb26\\php\\php.exe"

def sanitize_filename(name):
    name = re.sub(r'[^\w\s\-\(\)]', '_', name)
    return name.replace(" ", "_")[:120]

def resolve_short_link(url):
    try:
        r = requests.get(url, allow_redirects=True, timeout=10)
        return r.url
    except Exception as e:
        print(f"Error resolving link: {e}")
        return url

def get_album_metadata(url):
    try:
        resolved_url = resolve_short_link(url)
        print(f"Full URL: {resolved_url}")
        
        # Extract Album ID and Key
        album_id_match = re.search(r'share/(.*?)\?', resolved_url)
        key_match = re.search(r'key=(.*)', resolved_url)
        
        album_id = album_id_match.group(1) if album_id_match else ""
        album_key = key_match.group(1) if key_match else ""
        
        response = requests.get(resolved_url, timeout=15)
        content = response.text
        
        # Album Title
        title_match = re.search(r'<title>(.*?)</title>', content)
        album_title = title_match.group(1).replace(" - Google Photos", "").strip() if title_match else "Google_Photos_Album"
        
        # Extract ordered unique IDs (AF1Qip...)
        ordered_ids = [m.group(1) for m in re.finditer(r'\"(AF1Qip[^\"\s]{30,})\"', content) if m.group(1) != album_id]
        unique_ids = []
        [unique_ids.append(x) for x in ordered_ids if x not in unique_ids]
        
        # Extract ordered unique Image URLs (lh3...)
        ordered_imgs = [m.group(1) for m in re.finditer(r'\"(https://lh3\.googleusercontent\.com/pw/[^\"\s]+)\"', content) if "=w" not in m.group(1)]
        unique_imgs = []
        [unique_imgs.append(x) for x in ordered_imgs if x not in unique_imgs]
        
        print(f"Found {len(unique_ids)} photo IDs and {len(unique_imgs)} image sources.")
        
        # If lengths match, we can pair them. Otherwise, we'll just use images.
        # Usually they match perfectly if the page loads correctly.
        paired_data = []
        for pid, pimg in zip(unique_ids, unique_imgs):
            share_link = f"https://photos.google.com/share/{album_id}/photo/{pid}?key={album_key}"
            paired_data.append({
                'id': pid,
                'img_src': pimg,
                'share_link': share_link
            })
            
        return {
            'title': album_title,
            'photos': paired_data
        }
    except Exception as e:
        print(f"Error fetching metadata: {e}")
        return None

def download_image(args):
    img_url, save_path = args
    try:
        dl_url = img_url + "=w2400"
        r = requests.get(dl_url, stream=True, timeout=20)
        if r.status_code == 200:
            with open(save_path, 'wb') as f:
                for chunk in r.iter_content(chunk_size=16384):
                    f.write(chunk)
            return True
    except Exception as e:
        print(f"Failed to download image: {e}")
    return False

def process_album(url):
    metadata = get_album_metadata(url)
    if not metadata or not metadata['photos']:
        print("Error: Could not retrieve album data.")
        return

    album_title = metadata['title']
    photos = metadata['photos']
    
    print(f"Album Found: {album_title}")
    
    folder_name = sanitize_filename(album_title) + "_" + datetime.now().strftime("%H%M%S")
    target_dir = os.path.join(UPLOADS_DIR, folder_name)
    if not os.path.exists(target_dir):
        os.makedirs(target_dir)

    # In "CDN Mode", we only download the FIRST image locally as a thumbnail
    print("Downloading local cover image...")
    cover_photo = photos[0]
    cover_filename = "cover.jpg"
    cover_path = os.path.join(target_dir, cover_filename)
    if download_image((cover_photo['img_src'], cover_path)):
        print(f"Cover image saved to: {folder_name}/cover.jpg")
    
    # Save the mapping to a JSON file for PHP to ingest
    link_file = os.path.join(TEMP_DIR, f"links_{folder_name}.json")
    with open(link_file, "w", encoding="utf-8") as f:
        json.dump(photos, f)
    
    print(f"Metadata saved to: {os.path.basename(link_file)}")
    
    # Trigger PHP ingestion in CDN mode
    trigger_ingestion(folder_name, album_title, link_file)

def trigger_ingestion(folder_name, display_name, link_file):
    print("Starting database ingestion (CDN Mode)...")
    try:
        cmd_php = [LOCAL_PHP, INGEST_SCRIPT, folder_name, display_name, "--cdn", link_file]
        result = subprocess.run(cmd_php, capture_output=True, text=True, check=True)
        print(result.stdout)
        print("Database sync complete.")
        
        # Cleanup temp file
        # os.remove(link_file)
    except Exception as e:
        print(f"Ingestion failed: {e}")

def main():
    if len(sys.argv) > 1:
        url = sys.argv[1]
        process_album(url)
    else:
        print("Usage: python import_bulk.py [google_photos_url]")

if __name__ == "__main__":
    main()
