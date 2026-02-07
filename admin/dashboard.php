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
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="glass">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <a href="../index.php" class="logo">ADMIN<span>PANEL</span></a>
            <nav>
                <ul>
                    <li><a href="dashboard.php" class="active">Dashboard</a></li>
                    <li><a href="upload_event.php">Upload</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
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

        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 4rem;">
            <div>
                <h2 style="font-size: 3.5rem; line-height: 1;">Control Center</h2>
                <p style="color: var(--text-muted); margin-top: 1rem;">Managing <?php echo count($events); ?> photographic events.</p>
            </div>
            <a href="upload_event.php" class="btn btn-primary">+ New Event</a>
        </div>

        <div class="glass-card" style="padding: 2rem; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: 600px;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--glass-border); color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em;">
                        <th style="padding: 1.5rem;">Event Identity</th>
                        <th style="padding: 1.5rem;">Timeline</th>
                        <th style="padding: 1.5rem;">Assets</th>
                        <th style="padding: 1.5rem; text-align: right;">Operations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($events)): ?>
                        <tr>
                            <td colspan="4" style="padding: 4rem; text-align: center; color: var(--text-muted);">No collections established yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($events as $event): ?>
                            <tr style="border-bottom: 1px solid var(--glass-border); transition: var(--transition);" onmouseover="this.style.background='var(--glass)'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 1.5rem;">
                                    <div style="font-weight: 700; color: var(--text-main); font-size: 1.1rem;"><?php echo htmlspecialchars($event['name']); ?></div>
                                    <div style="font-size: 0.8rem; color: var(--text-muted);"><?php echo htmlspecialchars($event['folder_name']); ?></div>
                                </td>
                                <td style="padding: 1.5rem; font-weight: 500;"><?php echo date('M Y', strtotime($event['event_date'])); ?></td>
                                <td style="padding: 1.5rem;"><span style="background: var(--glass-bright); padding: 0.3rem 0.8rem; border-radius: 100px; font-size: 0.8rem;"><?php echo $event['photo_count']; ?> Photos</span></td>
                                <td style="padding: 1.5rem; text-align: right;">
                                    <a href="delete_event.php?id=<?php echo $event['id']; ?>" onclick="return confirm('Archive this event?')" class="btn btn-outline" style="padding: 0.5rem 1.2rem; font-size: 0.8rem; color: var(--primary); border-color: rgba(255, 71, 87, 0.2);">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
