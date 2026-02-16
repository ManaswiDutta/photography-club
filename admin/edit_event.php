<?php
require_once '../includes/db.php';
require_once 'auth.php';
checkLogin();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: dashboard.php");
    exit();
}

// Fetch event details
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$id]);
$event = $stmt->fetch();

if (!$event) {
    header("Location: dashboard.php");
    exit();
}

$message = '';
$status = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $event_date = $_POST['event_date'] ?? '';
    $description = $_POST['description'] ?? '';
    $cover_image = $_POST['cover_image'] ?? '';

    try {
        $stmt = $pdo->prepare("UPDATE events SET name = ?, event_date = ?, description = ?, cover_image = ? WHERE id = ?");
        $stmt->execute([$name, $event_date . "-01", $description, $cover_image, $id]);
        $message = "Event updated successfully!";
        $status = "success";
        
        // Refresh event data
        $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$id]);
        $event = $stmt->fetch();
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $status = "error";
    }
}

// Fetch all photos for this event
$photo_stmt = $pdo->prepare("SELECT * FROM photos WHERE event_id = ?");
$photo_stmt->execute([$id]);
$photos = $photo_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event | Photo Club</title>
    <meta name="referrer" content="no-referrer">
    <link rel="stylesheet" href="../assets/css/style.css?v=2.1">
    <style>
        .thumbnail-option {
            position: relative;
            cursor: pointer;
            border-radius: 12px;
            overflow: hidden;
            border: 3px solid transparent;
            transition: var(--transition);
        }
        .thumbnail-option.active {
            border-color: var(--primary);
        }
        .thumbnail-option img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .thumbnail-option .check-mark {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: var(--primary);
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: bold;
            opacity: 0;
            transition: 0.3s;
        }
        .thumbnail-option.active .check-mark {
            opacity: 1;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

    <main class="container animate-up" style="padding-top: 10rem; padding-bottom: 5rem;">
        <div style="max-width: 900px; margin: 0 auto;">
            <div style="margin-bottom: 3rem;">
                <h2 style="font-size: 3rem; margin-bottom: 0.5rem;">Edit Event</h2>
                <p style="color: var(--text-muted);">Refine metadata and select the perfect thumbnail.</p>
            </div>

            <?php if ($message): ?>
                <div class="glass" style="padding: 1.2rem 2rem; border-radius: 12px; margin-bottom: 2rem; border-color: <?php echo ($status == 'success') ? '#2ecc71' : 'var(--primary)'; ?>;">
                    <p style="color: <?php echo ($status == 'success') ? '#2ecc71' : 'var(--primary)'; ?>; font-weight: 600;">
                        <?php echo ($status == 'success') ? '✓ ' : '× '; ?><?php echo $message; ?>
                    </p>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="glass-card" style="padding: 3rem; margin-bottom: 3rem;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                        <div>
                            <label style="display: block; margin-bottom: 0.8rem; color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase;">Event Name</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($event['name']); ?>" required>
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 0.8rem; color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase;">Event Timeline</label>
                            <input type="month" name="event_date" value="<?php echo date('Y-m', strtotime($event['event_date'])); ?>" required>
                        </div>
                    </div>
                    <div style="margin-bottom: 2rem;">
                        <label style="display: block; margin-bottom: 0.8rem; color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase;">Description</label>
                        <textarea name="description" rows="4"><?php echo htmlspecialchars($event['description']); ?></textarea>
                    </div>

                    <label style="display: block; margin-bottom: 1.5rem; color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase;">Select Cover Image</label>
                    <input type="hidden" name="cover_image" id="cover_image_input" value="<?php echo htmlspecialchars($event['cover_image']); ?>">
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; max-height: 400px; overflow-y: auto; padding: 1rem; background: rgba(0,0,0,0.2); border-radius: 16px;">
                        <?php foreach ($photos as $photo): ?>
                            <div class="thumbnail-option <?php echo ($event['cover_image'] == $photo['file_path']) ? 'active' : ''; ?>" 
                                 onclick="selectThumbnail(this, '<?php echo addslashes($photo['file_path']); ?>')">
                                <?php $src = (strpos($photo['file_path'], 'http') === 0) ? $photo['file_path'] : '../' . $photo['file_path']; ?>
                                <img src="<?php echo htmlspecialchars($src); ?>" alt="Option">
                                <div class="check-mark">✓</div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div style="display: flex; gap: 1.5rem;">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="dashboard.php" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <script>
        function selectThumbnail(el, path) {
            // Remove active class from all
            document.querySelectorAll('.thumbnail-option').forEach(item => item.classList.remove('active'));
            // Add to current
            el.classList.add('active');
            // Update hidden input
            document.getElementById('cover_image_input').value = path;
        }
    </script>
<?php include 'footer.php'; ?>
