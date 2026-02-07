<?php
require_once '../includes/db.php';
require_once 'auth.php';
checkLogin();

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['folder_files'])) {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date']; // Expecting YYYY-MM
    $description = $_POST['description'];
    
    // Create folder name: event_name_month_year
    $date_obj = DateTime::createFromFormat('Y-m', $event_date);
    $folder_suffix = $date_obj->format('M_Y');
    $clean_event_name = preg_replace('/[^a-zA-Z0-9]/', '_', strtolower($event_name));
    $folder_name = $clean_event_name . "_" . $folder_suffix;
    
    $upload_dir = '../uploads/' . $folder_name . '/';
    
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO events (name, event_date, folder_name, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$event_name, $event_date . "-01", $folder_name, $description]);
        $event_id = $pdo->lastInsertId();

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $photo_stmt = $pdo->prepare("INSERT INTO photos (event_id, file_path) VALUES (?, ?)");
        $first_photo = null;

        foreach ($_FILES['folder_files']['name'] as $key => $name) {
            $tmp_name = $_FILES['folder_files']['tmp_name'][$key];
            $type = $_FILES['folder_files']['type'][$key];
            
            if (in_array($type, $allowed_types)) {
                $target_file = $upload_dir . basename($name);
                if (move_uploaded_file($tmp_name, $target_file)) {
                    $relative_path = 'uploads/' . $folder_name . '/' . basename($name);
                    $photo_stmt->execute([$event_id, $relative_path]);
                    if (!$first_photo) $first_photo = $relative_path;
                }
            }
        }
        
        // Update cover image with the first photo uploaded
        if ($first_photo) {
            $pdo->prepare("UPDATE events SET cover_image = ? WHERE id = ?")->execute([$first_photo, $event_id]);
        }

        $pdo->commit();
        $_SESSION['success_msg'] = "Event and photos uploaded successfully!";
        header("Location: dashboard.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "Error: " . $e->getMessage();
        $upload_success = false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Event | Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="glass">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <a href="../index.php" class="logo">ADMIN<span>PANEL</span></a>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="upload_event.php" class="active">Upload</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container animate-up" style="padding-top: 10rem;">
        <div class="glass-card" style="max-width: 700px; margin: 0 auto; padding: 4rem;">
            <div style="margin-bottom: 3rem;">
                <h2 style="font-size: 2.5rem; margin-bottom: 0.5rem;">Upload Memories</h2>
                <p style="color: var(--text-muted);">Select a folder to bulk-upload photos to a new event.</p>
            </div>
            
            <?php if ($message): ?>
                <div class="glass" style="padding: 1rem; border-color: var(--primary); margin-bottom: 2rem;">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Event Name</label>
                    <input type="text" name="event_name" required style="width: 100%; padding: 0.8rem; background: var(--bg-card); border: 1px solid var(--glass-border); border-radius: 8px; color: white;" placeholder="e.g. Annual Fest 2026">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Event Month & Year</label>
                    <input type="month" name="event_date" required style="width: 100%; padding: 0.8rem; background: var(--bg-card); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Description</label>
                    <textarea name="description" rows="3" style="width: 100%; padding: 0.8rem; background: var(--bg-card); border: 1px solid var(--glass-border); border-radius: 8px; color: white;" placeholder="Brief details about the event..."></textarea>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Select Event Folder</label>
                    <input type="file" name="folder_files[]" webkitdirectory mozdirectory directory multiple required style="width: 100%; padding: 0.8rem; background: var(--bg-card); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem;">Note: This will upload all images inside the selected folder.</p>
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">Start Upload</button>
            </form>
        </div>
    </main>
</body>
</html>
