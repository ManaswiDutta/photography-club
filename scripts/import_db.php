<?php
// scripts/import_db.php
require_once __DIR__ . '/../includes/db.php';

// Usage: php import_db.php [folder_name] [display_name] [--cdn] [link_file]
$folder_name = $argv[1] ?? null;
$display_name = $argv[2] ?? null;
$is_cdn = in_array('--cdn', $argv);
$link_file = $argv[4] ?? null;

if (!$folder_name || !$display_name) {
    die("Usage: php import_db.php [folder_name] [display_name] [--cdn] [link_file]\n");
}

$uploads_base = "uploads/";
$event_dir = $uploads_base . $folder_name;

// In CDN mode, we don't strictly require the whole folder to be full of images,
// but we expect a 'cover.jpg' to be there.
if (!is_dir(__DIR__ . "/../" . $event_dir)) {
    die("Folder not found: $event_dir\n");
}

$images = [];

if ($is_cdn && $link_file && file_exists($link_file)) {
    echo "Processing in CDN Mode...\n";
    $photos_data = json_decode(file_get_contents($link_file), true);
    if (!$photos_data) {
        die("Invalid link file: $link_file\n");
    }

    foreach ($photos_data as $p) {
        $images[] = [
            'path' => $p['img_src'] . "=w2048",
            'external' => $p['share_link']
        ];
    }
} else {
    echo "Processing in Local Mode...\n";
    // Find all images in the folder (including subdirectories)
    $full_path = realpath(__DIR__ . "/../" . $event_dir);
    $it = new RecursiveDirectoryIterator($full_path);
    $it_it = new RecursiveIteratorIterator($it);

    foreach($it_it as $file) {
        if (in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp'])) {
            $rel_path = $event_dir . "/" . str_replace($full_path . DIRECTORY_SEPARATOR, "", $file->getRealPath());
            $rel_path = str_replace("\\", "/", $rel_path);
            $images[] = [
                'path' => $rel_path,
                'external' => null
            ];
        }
    }
}

if (empty($images)) {
    die("No photos found to import.\n");
}

// Check if event already exists
$stmt = $pdo->prepare("SELECT id FROM events WHERE name = ?");
$stmt->execute([$display_name]);
$event = $stmt->fetch();

if (!$event) {
    // Determine cover image (prefer local 'cover.jpg' if in CDN mode)
    $cover = $images[0]['path'];
    if ($is_cdn && file_exists(__DIR__ . "/../" . $event_dir . "/cover.jpg")) {
        $cover = $event_dir . "/cover.jpg";
    }

    // Create new event
    $stmt = $pdo->prepare("INSERT INTO events (name, description, event_date, cover_image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$display_name, "Imported from Google Photos", date('Y-m-d'), $cover]);
    $event_id = $pdo->lastInsertId();
    echo "Created new event: $display_name\n";
} else {
    $event_id = $event['id'];
    echo "Updating existing event: $display_name\n";
}

// Ingest photos
$stmt = $pdo->prepare("INSERT IGNORE INTO photos (event_id, file_path, external_link) VALUES (?, ?, ?)");
$count = 0;
foreach ($images as $img) {
    if ($stmt->execute([$event_id, $img['path'], $img['external']])) {
        $count++;
    }
}

echo "Imported $count photos.\n";
?>
