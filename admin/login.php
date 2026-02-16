<?php
require_once '../includes/db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['username'] = $admin['username'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in | Photo Club Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=2.2">
</head>
<body class="login-page">
    <div class="login-card">
        <a href="../index.php" class="login-logo">PHOTO<span>CLUB</span></a>
        <p class="login-subtitle">Admin portal</p>
        <h1 class="login-title">Log in</h1>

        <?php if ($error): ?>
            <p class="login-error" role="alert"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST" class="login-form" autocomplete="on">
            <div class="field-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autocomplete="username" placeholder="Enter your username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            <div class="field-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="Enter your password">
            </div>
            <button type="submit" class="btn-submit">Log in</button>
        </form>

        <div class="login-footer">
            <a href="../index.php">← Back to site</a>
        </div>
    </div>
</body>
</html>
