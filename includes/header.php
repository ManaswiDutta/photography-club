<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<header>
    <div class="container">
        <a href="index.php" class="logo"><span>PHOTOGRAPHY </span>CLUB</a>
        <nav>
            <ul>
                <li><a href="index.php" class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                <li><a href="gallery.php" class="<?php echo $current_page == 'gallery.php' ? 'active' : ''; ?>">Gallery</a></li>
                <li><a href="admin/login.php">Admin</a></li>
            </ul>
        </nav>
        <div class="nav-toggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</header>