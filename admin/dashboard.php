<?php
require_once '../includes/db.php';
require_once 'auth.php';
checkLogin();

// Fetch events
$stmt = $pdo->query("SELECT e.*, (SELECT COUNT(*) FROM photos WHERE event_id = e.id) as photo_count FROM events e ORDER BY event_date DESC");
$events = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Photo Club</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=2.1">
</head>
<body>
    <header class="glass">
        <div class="container header-flex">
            <a href="dashboard.php" class="logo">ADMIN<span>PANEL</span></a>
            <nav>
                <ul>
                    <li><a href="dashboard.php" class="active">Dashboard</a></li>
                    <li><a href="upload_event.php">Upload</a></li>
                    <li><a href="background_settings.php">BG Settings</a></li>
                    <li><a href="social_settings.php">Social</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
            <div class="nav-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </header>

    <main class="container animate-up" style="padding-top: 10rem;">
        <?php if (isset($_SESSION['success_msg'])): ?>
            <div class="toast-success">
                ✓ <?php echo $_SESSION['success_msg']; ?>
                <?php unset($_SESSION['success_msg']); ?>
            </div>
            <script>
                setTimeout(() => {
                    document.querySelector('.toast-success').style.opacity = '0';
                    setTimeout(() => document.querySelector('.toast-success').remove(), 500);
                }, 4000);
            </script>
        <?php endif; ?>

        <div class="admin-page-header">
            <div>
                <h2>Control Center</h2>
                <p style="color: var(--text-muted); margin-top: 1rem;">Managing <?php echo count($events); ?> photographic events.</p>
            </div>
            <div class="admin-actions">
                <a href="background_settings.php" class="btn btn-outline" style="border-color: var(--primary); color: var(--primary);">Change Backgrounds</a>
                <a href="upload_event.php" class="btn btn-primary">+ New Event</a>
            </div>
        </div>

        <div class="glass-card" style="padding: 2rem; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--glass-border); color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em;">
                        <th style="padding: 1rem 0.5rem;">Event & Stats</th>
                        <th style="padding: 1rem 0.5rem;">Timeline</th>
                        <th style="padding: 1rem 0.5rem; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($events)): ?>
                        <tr>
                            <td colspan="3" style="padding: 4rem; text-align: center; color: var(--text-muted);">No collections established yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($events as $event): ?>
                            <tr style="border-bottom: 1px solid var(--glass-border); transition: var(--transition);" onmouseover="this.style.background='var(--glass)'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 1.2rem 0.5rem;">
                                    <div style="font-weight: 700; color: var(--text-main); font-size: 1rem; line-height: 1.2;"><?php echo htmlspecialchars($event['name']); ?></div>
                                    <div style="font-size: 0.75rem; color: var(--primary); margin-top: 0.3rem; font-weight: 600;"><?php echo $event['photo_count']; ?> Assets</div>
                                </td>
                                <td style="padding: 1.2rem 0.5rem; font-weight: 500; font-size: 0.85rem; color: var(--text-muted);"><?php echo date('M Y', strtotime($event['event_date'])); ?></td>
                                <td style="padding: 1.2rem 0.5rem; text-align: right; white-space: nowrap;">
                                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                        <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="btn-icon" title="Edit Event"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <a href="delete_event.php?id=<?php echo $event['id']; ?>" onclick="return confirm('Archive this event?')" class="btn-icon btn-icon-danger" title="Delete Event"><i class="fa-solid fa-trash-can"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
    <script src="../assets/js/main.js"></script>
</body>
</html>
