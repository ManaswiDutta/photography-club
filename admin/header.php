<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<header class="glass">
    <div class="container header-flex">
        <a href="dashboard.php" class="logo">ADMIN<span>PANEL</span></a>
        <nav>
            <ul>
                <li><a href="dashboard.php" class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a></li>
                <li><a href="upload_event.php" class="<?php echo $current_page == 'upload_event.php' ? 'active' : ''; ?>">Upload</a></li>
                <li><a href="background_settings.php" class="<?php echo $current_page == 'background_settings.php' ? 'active' : ''; ?>">BG Settings</a></li>
                <li><a href="social_settings.php" class="<?php echo $current_page == 'social_settings.php' ? 'active' : ''; ?>">Social</a></li>
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