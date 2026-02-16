<?php
// scripts/import_db.php
require_once __DIR__ . '/../includes/db.php';

if ($argc < 3) {
    die("Usage: php import_db.php [folder_name] [display_name]\n");
}

$folder_name = $argv[1];
$display_name = $argv[2];
$uploads_base = "uploads/";
$event_dir = $uploads_base . $folder_name;

if (!is_dir(__DIR__ . "/../" . $event_dir)) {
    die("Folder not found: $event_dir\n");
}

// Find all images in the folder (including subdirectories)
$full_path = realpath(__DIR__ . "/../" . $event_dir);
$it = new RecursiveDirectoryIterator($full_path);
$display = new RecursiveIteratorIterator($it);
$images = [];

foreach($display as $file) {
    if (in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp'])) {
        // Construct the relative path for the DB
        $rel_path = $event_dir . "/" . str_replace($full_path . DIRECTORY_SEPARATOR, "", $file->getRealPath());
        $rel_path = str_replace("\\", "/", $rel_path);
        $images[] = $rel_path;
    }
}

if (empty($images)) {
    die("No images found in $event_dir\n");
}

// Check if event already exists
$stmt = $pdo->prepare("SELECT id FROM events WHERE name = ?");
$stmt->execute([$display_name]);
$event = $stmt->fetch();

if (!$event) {
    // Create new event
    $cover = $images[0]; // Use first image as cover
    $stmt = $pdo->prepare("INSERT INTO events (name, description, event_date, cover_image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$display_name, "Bulk imported from Google Photos", date('Y-m-d'), $cover]);
    $event_id = $pdo->lastInsertId();
    echo "Created new event: $display_name\n";
} else {
    $event_id = $event['id'];
    echo "Updating existing event: $display_name\n";
}

// Ingest photos
$stmt = $pdo->prepare("INSERT IGNORE INTO photos (event_id, file_path) VALUES (?, ?)");
$count = 0;
foreach ($images as $path) {
    if ($stmt->execute([$event_id, $path])) {
        $count++;
    }
}

echo "Imported $count photos.\n";
?>
