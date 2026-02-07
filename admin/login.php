<?php
require_once '../includes/db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['username'] = $admin['username'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid credentials";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Photo Club</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; height: 100vh;">
    <div class="glass-card" style="width: 100%; max-width: 450px; padding: 4rem; text-align: center;">
        <div style="margin-bottom: 3rem;">
            <a href="../index.php" class="logo" style="display: block; margin-bottom: 0.5rem;">PHOTO<span>CLUB</span></a>
            <p style="color: var(--text-muted); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.1em;">Management Portal</p>
        </div>
        
        <?php if ($error): ?>
            <p style="color: var(--primary); margin-bottom: 2rem; font-weight: 600;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div style="text-align: left;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase;">Username</label>
                <input type="text" name="username" required>
            </div>
            <div style="text-align: left;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase;">Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1.5rem;">Authenticate</button>
        </form>
    </div>
</body>
</html>
