import requests
import sys

url = "https://lh3.googleusercontent.com/pw/AP1GczMMuBWICiMaESnxSaixnSOrrDGhgoKItI4o9f9aoX6YlMaJQMyteWRN21AbQwP7d5k9F83drQslmACXrVklLjicjQ2vpFfa6Ml5VcA85A0aJzJz1Kg=w2048"

try:
    print(f"Testing URL: {url}")
    r = requests.get(url, timeout=10)
    print(f"Status Code: {r.status_code}")
    if r.status_code == 200:
        print(f"Success! Content length: {len(r.content)}")
    else:
        print(f"Failed with status: {r.status_code}")
        print(f"Response snippet: {r.text[:200]}")
except Exception as e:
    print(f"Error: {e}")
