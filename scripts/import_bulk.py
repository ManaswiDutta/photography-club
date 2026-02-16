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

# Try to find the local PHP executable for more reliable ingestion
LOCAL_PHP = "php"
if os.path.exists("C:\\xampp_feb26\\php\\php.exe"):
    LOCAL_PHP = "C:\\xampp_feb26\\php\\php.exe"

def sanitize_filename(name):
    # Remove emojis and special chars that might break Windows paths
    name = re.sub(r'[^\w\s\-\(\)]', '_', name)
    return name.replace(" ", "_")[:120] # Limit length

def resolve_short_link(url):
    try:
        r = requests.get(url, allow_redirects=True, timeout=10)
        return r.url
    except Exception as e:
        print(f"Error resolving link: {e}")
        return url

def get_album_metadata(url):
    try:
        response = requests.get(url, timeout=15)
        content = response.text
        
        # Try to find album title
        title_match = re.search(r'<title>(.*?)</title>', content)
        album_title = title_match.group(1).replace(" - Google Photos", "").strip() if title_match else "Google_Photos_Album"
        
        # Extract image URLs using a more precise pattern for high-res images
        # Google Photos uses AF_initDataCallback for images
        pattern = r'\"(https://lh3\.googleusercontent\.com/pw/[^\"\s]+)\"'
        urls = list(set(re.findall(pattern, content)))
        
        # Filter for base URLs (usually longer, without = params)
        clean_urls = [u for u in urls if "=w" not in u and "=h" not in u]
        
        # Sort or filter if needed? Usually they are unsorted in the page
        return {
            'title': album_title,
            'urls': clean_urls
        }
    except Exception as e:
        print(f"Error fetching metadata: {e}")
        return None

def download_image(args):
    img_url, i, target_dir = args
    try:
        # w0 often grabs the original. w2400 is a good safe high-res fallback.
        dl_url = img_url + "=w2400" 
        filename = f"photo_{i+1:03d}.jpg"
        save_path = os.path.join(target_dir, filename)
        
        r = requests.get(dl_url, stream=True, timeout=20)
        if r.status_code == 200:
            with open(save_path, 'wb') as f:
                for chunk in r.iter_content(chunk_size=16384):
                    f.write(chunk)
            return True
    except Exception as e:
        print(f"   Failed image {i+1}: {e}")
    return False

def download_album(url):
    if not url.startswith("http"):
        return

    print(f"\n--- Initializing Import for: {url} ---")
    
    resolved_url = resolve_short_link(url)
    metadata = get_album_metadata(resolved_url)
    
    if not metadata or not metadata['urls']:
        print("Error: Could not extract photo data. Is the album link public?")
        return

    album_title = metadata['title']
    image_urls = metadata['urls']
    print(f"Album Found: {album_title}")
    print(f"Total Photos: {len(image_urls)}")

    folder_name = sanitize_filename(album_title)
    # Unique timestamped folder
    folder_name += "_" + datetime.now().strftime("%H%M%S")
    
    target_dir = os.path.join(UPLOADS_DIR, folder_name)
    if not os.path.exists(target_dir):
        os.makedirs(target_dir)

    print(f"Starting multi-threaded download to: {folder_name}")
    
    # Use ThreadPoolExecutor for parallel downloads
    # 8 threads is usually a good balance for I/O
    tasks = [(url, i, target_dir) for i, url in enumerate(image_urls)]
    downloaded_count = 0
    
    with ThreadPoolExecutor(max_workers=8) as executor:
        results = list(executor.map(download_image, tasks))
        downloaded_count = sum(1 for r in results if r)

    if downloaded_count > 0:
        print(f"\n--- Success! ---")
        print(f"Downloaded {downloaded_count} photos.")
        trigger_ingestion(folder_name, album_title)
    else:
        print("\n--- Failure ---")
        print("No photos were downloaded.")

def trigger_ingestion(folder_name, display_name):
    print(f"Syncing with database...")
    try:
        cmd_php = [LOCAL_PHP, INGEST_SCRIPT, folder_name, display_name]
        result = subprocess.run(cmd_php, capture_output=True, text=True, check=True)
        print(result.stdout)
        print("Database sync complete.")
    except Exception as e:
        print(f"Ingestion failed: {e}")

def main():
    if len(sys.argv) > 1:
        url = sys.argv[1]
        if url.startswith("http"):
            download_album(url)
        else:
            print("Invalid URL format.")
    else:
        print("Usage: python import_bulk.py [google_shared_album_url]")

if __name__ == "__main__":
    main()
