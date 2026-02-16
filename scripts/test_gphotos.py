import requests
import re
import sys

def get_google_photos_urls(url):
    print(f"Resolving {url}...")
    r = requests.get(url, allow_redirects=True)
    resolved_url = r.url
    print(f"Full URL: {resolved_url}")
    
    # Extract Album ID and Key from resolved URL
    # https://photos.google.com/share/AF1QipM... ?key=ZHBm...
    album_id_match = re.search(r'share/(.*?)\?', resolved_url)
    key_match = re.search(r'key=(.*)', resolved_url)
    
    album_id = album_id_match.group(1) if album_id_match else ""
    album_key = key_match.group(1) if key_match else ""
    
    print(f"Album ID: {album_id}")
    print(f"Key: {album_key}")

    content = requests.get(resolved_url).text
    
    # Extract all long AF1Qip hashes
    all_ids = list(set(re.findall(r'\"(AF1Qip[^\"\s]{30,})\"', content)))
    
    # Filter out the Album ID
    photo_ids = [i for i in all_ids if i != album_id]
    
    # Extract all lh3 base URLs
    img_urls = list(set(re.findall(r'\"(https://lh3\.googleusercontent\.com/pw/[^\"\s]+)\"', content)))
    img_urls = [u for u in img_urls if "=w" not in u and "=h" not in u]
    
    print(f"Photo IDs: {len(photo_ids)}")
    print(f"Image URLs: {len(img_urls)}")
    
    # Construct pairs (Note: Order might be tricky, but usually consistent in DOM)
    # Actually, zip is safe if they are extracted in order. 
    # Let's use re.finditer to ensure order.
    
    ordered_ids = [m.group(1) for m in re.finditer(r'\"(AF1Qip[^\"\s]{30,})\"', content) if m.group(1) != album_id]
    # Remove duplicates while preserving order
    unique_ids = []
    [unique_ids.append(x) for x in ordered_ids if x not in unique_ids]
    
    ordered_imgs = [m.group(1) for m in re.finditer(r'\"(https://lh3\.googleusercontent\.com/pw/[^\"\s]+)\"', content) if "=w" not in m.group(1)]
    unique_imgs = []
    [unique_imgs.append(x) for x in ordered_imgs if x not in unique_imgs]
    
    print(f"Unique Ordered IDs: {len(unique_ids)}")
    print(f"Unique Ordered Imgs: {len(unique_imgs)}")
    
    for pid, pimg in zip(unique_ids[:5], unique_imgs[:5]):
        share_link = f"https://photos.google.com/share/{album_id}/photo/{pid}?key={album_key}"
        print(f"ID: {pid}")
        print(f"Link: {share_link}\n")
    
    return list(zip(unique_ids, unique_imgs))

if __name__ == "__main__":
    if len(sys.argv) > 1:
        get_google_photos_urls(sys.argv[1])
