<?php
require_once 'includes/db.php';

$username = 'admin';
$password = 'admin123'; // User should change this
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$setup_message = '';
try {
    $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $hashed_password]);
    $setup_message = "<p style='color: #2ecc71; font-weight: 700; margin-bottom: 1rem;'>✓ Default admin created.</p>
                      User: <b>admin</b><br>
                      Pass: <b>admin123</b><br><br>
                      <p style='color: #ff4757; font-size: 0.8rem;'>CRITICAL: Delete setup.php now.</p>";
} catch (PDOException $e) {
    $setup_message = "<p style='color: #ff4757;'>Error: " . $e->getMessage() . "</p>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Setup | Photo Club</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; height: 100vh;">
    <div class="glass-card" style="padding: 4rem; text-align: center; max-width: 500px;">
        <h2 style="margin-bottom: 2rem; color: var(--primary);">System Setup</h2>
        <div style="text-align: left; background: var(--glass); padding: 2rem; border-radius: 12px; font-family: 'Inter', sans-serif;">
            <?php echo $setup_message; ?>
        </div>
        <a href="admin/login.php" class="btn btn-primary" style="margin-top: 2rem; width: 100%;">Go to Login</a>
    </div>
</body>
</html>
