<?php
require_once '../includes/db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";

// Handle Background Selection
if (isset($_POST['toggle_bg'])) {
    $photo_id = $_POST['photo_id'];
    
    // Check if already in backgrounds
    $check = $pdo->prepare("SELECT id FROM backgrounds WHERE photo_id = ?");
    $check->execute([$photo_id]);
    
    if ($check->fetch()) {
        // Remove
        $stmt = $pdo->prepare("DELETE FROM backgrounds WHERE photo_id = ?");
        $stmt->execute([$photo_id]);
        $message = "Photo removed from background rotation.";
    } else {
        // Add
        $stmt = $pdo->prepare("INSERT INTO backgrounds (photo_id) VALUES (?)");
        $stmt->execute([$photo_id]);
        $message = "Photo added to background rotation.";
    }
}

// Fetch all events for filtering
$events_stmt = $pdo->query("SELECT id, name FROM events ORDER BY event_date DESC");
$events = $events_stmt->fetchAll();

$selected_event = isset($_GET['event_id']) ? $_GET['event_id'] : (isset($events[0]) ? $events[0]['id'] : 0);

// Fetch photos for selected event
$photos = [];
if ($selected_event) {
    try {
        $photos_stmt = $pdo->prepare("SELECT p.*, (SELECT 1 FROM backgrounds b WHERE b.photo_id = p.id) as is_bg FROM photos p WHERE event_id = ? ORDER BY id DESC");
        $photos_stmt->execute([$selected_event]);
        $photos = $photos_stmt->fetchAll();
    } catch (PDOException $e) {
        $photos_stmt = $pdo->prepare("SELECT *, 0 as is_bg FROM photos WHERE event_id = ? ORDER BY id DESC");
        $photos_stmt->execute([$selected_event]);
        $photos = $photos_stmt->fetchAll();
    }
}

// Fetch current backgrounds for the header list
$current_bgs = [];
try {
    $current_bgs_stmt = $pdo->query("SELECT p.file_path, b.id as bg_id, p.id as photo_id FROM backgrounds b JOIN photos p ON b.photo_id = p.id");
    if ($current_bgs_stmt) {
        $current_bgs = $current_bgs_stmt->fetchAll();
    }
} catch (PDOException $e) {
    $current_bgs = [];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Background Settings - PH. Admin</title>
    <meta name="referrer" content="no-referrer">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-container { padding-top: 120px; max-width: 1200px; margin: 0 auto; }
        .bg-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 2rem; }
        .bg-item { position: relative; border-radius: 12px; overflow: hidden; aspect-ratio: 1; border: 2px solid transparent; transition: var(--transition); cursor: pointer; }
        .bg-item.active { border-color: var(--primary); box-shadow: 0 0 20px var(--primary-dim); }
        .bg-item img { width: 100%; height: 100%; object-fit: cover; }
        .bg-item .badge { position: absolute; top: 10px; right: 10px; background: var(--primary); color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.7rem; font-weight: 700; display: none; }
        .bg-item.active .badge { display: block; }
        .current-selection { background: var(--bg-card); padding: 2rem; border-radius: 20px; border: 1px solid var(--glass-border); margin-bottom: 3rem; }
        .horiz-scroll { display: flex; gap: 1.5rem; overflow-x: auto; padding: 1rem 0; }
        .horiz-scroll img { height: 100px; border-radius: 12px; border: 1px solid var(--glass-border); transition: 0.3s; }
        
        .bg-wrapper { position: relative; flex-shrink: 0; }
        .bg-wrapper:hover img { filter: brightness(0.7) blur(1px); }
        .remove-btn { 
            position: absolute; 
            top: -8px; 
            right: -8px; 
            background: #ff4757; 
            color: white; 
            border: 2px solid var(--bg-card); 
            width: 26px; 
            height: 26px; 
            border-radius: 50%; 
            cursor: pointer; 
            font-size: 16px; 
            line-height: 1;
            display: flex; 
            align-items: center; 
            justify-content: center; 
            opacity: 0; 
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 5;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        .bg-wrapper:hover .remove-btn { opacity: 1; transform: scale(1.1); }
        .remove-btn:hover { background: #ff6b81; transform: scale(1.3) !important; }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

    <main class="admin-container">
        <div class="admin-page-header">
            <div>
                <h1>Backgrounds</h1>
                <p style="color: var(--text-muted); margin-bottom: 2rem;">Featured in the homepage background scroller.</p>
            </div>
        </div>
        <div class="glass-card">

            <?php if ($message): ?>
                <div class="toast-success"><?php echo $message; ?></div>
            <?php endif; ?>

            <div class="current-selection">
                <h3 style="margin-bottom: 1.5rem; font-size: 1rem; text-transform: uppercase; letter-spacing: 0.1em;">Currently in Rotation (<?php echo count($current_bgs); ?>)</h3>
                <div class="horiz-scroll">
                    <?php if (empty($current_bgs)): ?>
                        <p style="color: var(--text-muted); font-size: 0.9rem;">No backgrounds selected. Homepage will use defaults.</p>
                    <?php else: ?>
                        <?php foreach ($current_bgs as $bg): ?>
                            <div class="bg-wrapper">
                                <?php $src = (strpos($bg['file_path'], 'http') === 0) ? $bg['file_path'] : '../' . $bg['file_path']; ?>
                                <img src="<?php echo htmlspecialchars($src); ?>" alt="Background">
                                <form action="" method="POST">
                                    <input type="hidden" name="photo_id" value="<?php echo $bg['photo_id']; ?>">
                                    <input type="hidden" name="toggle_bg" value="1">
                                    <button type="submit" class="remove-btn" title="Remove from Rotation">&times;</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <form action="" method="GET">
                    <label style="margin-right: 1rem; color: var(--text-muted);">Select Event:</label>
                    <select name="event_id" onchange="this.form.submit()" style="width: auto; min-width: 250px;">
                        <?php foreach ($events as $event): ?>
                            <option value="<?php echo $event['id']; ?>" <?php echo $selected_event == $event['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($event['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>

            <div class="bg-grid">
                <?php foreach ($photos as $photo): ?>
                    <form action="" method="POST" style="display: contents;">
                        <input type="hidden" name="photo_id" value="<?php echo $photo['id']; ?>">
                        <input type="hidden" name="toggle_bg" value="1">
                        <div class="bg-item <?php echo $photo['is_bg'] ? 'active' : ''; ?>" onclick="this.parentNode.submit()">
                            <?php $src = (strpos($photo['file_path'], 'http') === 0) ? $photo['file_path'] : '../' . $photo['file_path']; ?>
                            <img src="<?php echo htmlspecialchars($src); ?>" alt="Photo">
                            <div class="badge">ACTIVE</div>
                        </div>
                    </form>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <script>
        // Auto-hide toast
        setTimeout(() => {
            const toast = document.querySelector('.toast-success');
            if (toast) toast.style.display = 'none';
        }, 3000);
    </script>
<?php include 'footer.php'; ?>
