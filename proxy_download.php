<?php
/**
 * proxy_download.php
 * Handles cross-origin image downloads for Google Photos CDN links.
 */

if (!isset($_GET['url'])) {
    die("No URL provided.");
}

$url = $_GET['url'];

// Basic validation: must be a Google Photos CDN link (lh3.googleusercontent.com)
if (strpos($url, 'lh3.googleusercontent.com') === false && strpos($url, 'photos.google.com') === false) {
    // If it's a local file, we can serve it directly, but this proxy is mainly for remote
    if (strpos($url, 'uploads/') === 0) {
        $full_path = __DIR__ . '/' . $url;
        if (file_exists($full_path)) {
            $filename = basename($full_path);
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            readfile($full_path);
            exit();
        }
    }
    die("Invalid or unauthorized URL.");
}

// Extract filename or use a default
$filename = "photo_club_" . time() . ".jpg";

// Fetch the remote image
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
curl_setopt($ch, CURLOPT_REFERER, ''); // No referrer to bypass hotlinking protection

$data = curl_exec($ch);
$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
curl_close($ch);

if ($data === false) {
    die("Failed to fetch image.");
}

// Serve as a download
header('Content-Description: File Transfer');
header('Content-Type: ' . ($content_type ?: 'image/jpeg'));
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . strlen($data));

echo $data;
exit();
