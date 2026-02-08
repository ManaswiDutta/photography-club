<?php
require_once '../includes/db.php';
require_once 'auth.php';
checkLogin();

// Handle additions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_link'])) {
    $platform = $_POST['platform'];
    $url = $_POST['url'];
    
    if (!empty($platform) && !empty($url)) {
        $stmt = $pdo->prepare("INSERT INTO social_links (platform, url) VALUES (?, ?)");
        $stmt->execute([$platform, $url]);
        $_SESSION['success_msg'] = "Social link established.";
        header("Location: social_settings.php");
        exit();
    }
}

// Handle deletions
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM social_links WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $_SESSION['success_msg'] = "Social link archived.";
    header("Location: social_settings.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM social_links ORDER BY created_at DESC");
$links = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Settings | Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=2.1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="glass">
        <div class="container header-flex">
            <a href="dashboard.php" class="logo">SOCIAL<span>SETTINGS</span></a>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="upload_event.php">Upload</a></li>
                    <li><a href="background_settings.php">BG Settings</a></li>
                    <li><a href="social_settings.php" class="active">Social</a></li>
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
                    const toast = document.querySelector('.toast-success');
                    if (toast) {
                        toast.style.opacity = '0';
                        setTimeout(() => toast.remove(), 500);
                    }
                }, 4000);
            </script>
        <?php endif; ?>

        <div class="admin-page-header">
            <div>
                <h2>Digital Presence</h2>
                <p style="color: var(--text-muted); max-width: 600px;">Manage the social nodes that connect your collective to the digital expanse.</p>
            </div>
        </div>

        <div class="event-grid" style="grid-template-columns: 1fr 1.5fr; gap: 4rem;">
            <!-- Add Form -->
            <div class="glass-card" style="padding: 3rem;">
                <h3 style="font-size: 1.5rem; margin-bottom: 2rem; color: var(--primary);">Add Connection</h3>
                <form method="POST">
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted);">Platform Name</label>
                        <select name="platform" required>
                            <option value="Instagram">Instagram</option>
                            <option value="Facebook">Facebook</option>
                            <option value="Twitter">Twitter / X</option>
                            <option value="LinkedIn">LinkedIn</option>
                            <option value="YouTube">YouTube</option>
                            <option value="GitHub">GitHub</option>
                            <option value="Website">Personal Website</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 2rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted);">URL</label>
                        <input type="text" name="url" placeholder="https://instagram.com/yourclub" required>
                    </div>
                    <button type="submit" name="add_link" class="btn btn-primary" style="width: 100%;">Establish Link</button>
                </form>
            </div>

            <!-- Existing Links -->
            <div class="glass-card" style="padding: 3rem;">
                <h3 style="font-size: 1.5rem; margin-bottom: 2rem;">Active Nodes</h3>
                <?php if (empty($links)): ?>
                    <p style="color: var(--text-muted); padding: 2rem; border: 1px dashed var(--glass-border); border-radius: 12px; text-align: center;">No social connections established yet.</p>
                <?php else: ?>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <?php foreach ($links as $link): ?>
                            <div class="social-link-node">
                                <div class="platform-info">
                                    <?php
                                    $icon = "fa-link";
                                    switch(strtolower($link['platform'])) {
                                        case 'instagram': $icon = "fa-brands fa-instagram"; break;
                                        case 'facebook': $icon = "fa-brands fa-facebook"; break;
                                        case 'twitter': $icon = "fa-brands fa-twitter"; break;
                                        case 'linkedin': $icon = "fa-brands fa-linkedin"; break;
                                        case 'youtube': $icon = "fa-brands fa-youtube"; break;
                                        case 'github': $icon = "fa-brands fa-github"; break;
                                    }
                                    ?>
                                    <div style="width: 40px; height: 40px; background: var(--primary-dim); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--primary); flex-shrink: 0;">
                                        <i class="<?php echo $icon; ?>" style="font-size: 1.1rem;"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; font-size: 1rem; color: var(--text-main);"><?php echo htmlspecialchars($link['platform']); ?></div>
                                        <div class="url-text"><?php echo htmlspecialchars($link['url']); ?></div>
                                    </div>
                                </div>
                                <a href="?delete=<?php echo $link['id']; ?>" class="delete-link" onclick="return confirm('Archive this connection?')" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-muted)'">
                                    <i class="fa-solid fa-xmark"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <script src="../assets/js/main.js"></script>
</body>
</html>
