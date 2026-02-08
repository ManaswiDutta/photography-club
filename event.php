<?php 
require_once 'includes/db.php'; 

if (!isset($_GET['id'])) {
    header("Location: gallery.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$id]);
$event = $stmt->fetch();

if (!$event) {
    header("Location: gallery.php");
    exit();
}

$photo_stmt = $pdo->prepare("SELECT * FROM photos WHERE event_id = ?");
$photo_stmt->execute([$id]);
$photos = $photo_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['name']); ?> | Photo Club</title>
    <link rel="stylesheet" href="assets/css/style.css?v=2.1">
    <style>
        .photo-gallery {
            column-count: 3;
            column-gap: 2rem;
            width: 100%;
        }
        .photo-item {
            break-inside: avoid;
            margin-bottom: 2rem;
            cursor: pointer;
            transition: var(--transition);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--glass-border);
            position: relative;
        }
        .photo-item:hover {
            transform: translateY(-8px);
            border-color: var(--primary);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }
        .photo-item img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .photo-item:hover img {
            transform: scale(1.05);
        }
        @media (max-width: 1024px) {
            .photo-gallery { column-count: 2; }
        }
        @media (max-width: 600px) {
            .photo-gallery { column-count: 1; }
        }
        /* Refined Lightbox */
        #lightbox {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.98);
            z-index: 2000;
            justify-content: center;
            align-items: center;
            padding: 4rem;
        }
        #lightbox img {
            max-width: 90%;
            max-height: 85vh;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.8);
            animation: animateZoom 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        }
        @keyframes animateZoom {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        .nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: var(--glass);
            border: 1px solid var(--glass-border);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.5rem;
            transition: var(--transition);
            z-index: 2100;
        }
        .nav-btn:hover {
            background: var(--primary);
            border-color: var(--primary);
        }
        .prev-btn { left: 2rem; }
        .next-btn { right: 2rem; }
        .close-btn { 
            position: absolute; top: 2rem; right: 2rem; 
            font-size: 2rem; color: white; cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <a href="index.php" class="logo">PH<span>.CLUB</span></a>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="gallery.php">Gallery</a></li>
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

    <main class="container animate-up" style="min-height: 80vh; padding-top: 10rem;">
        <div style="margin-bottom: 6rem; text-align: left; max-width: 800px;">
            <a href="gallery.php" style="color: var(--primary); font-weight: 600; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.1em; display: inline-block; margin-bottom: 1.5rem;">&larr; Back to Gallery</a>
            <h1 style="font-size: 4.5rem; margin-bottom: 1rem; line-height: 1;"><?php echo htmlspecialchars($event['name']); ?></h1>
            <p style="color: var(--primary); font-weight: 700; font-size: 1.1rem; margin-bottom: 1.5rem;"><?php echo date('F Y', strtotime($event['event_date'])); ?></p>
            <p style="color: var(--text-muted); font-size: 1.1rem; font-weight: 300;"><?php echo htmlspecialchars($event['description']); ?></p>
        </div>

        <div class="photo-gallery">
            <?php foreach ($photos as $index => $photo): ?>
                <div class="photo-item reveal" onclick="openLightbox(<?php echo $index; ?>)">
                    <img src="<?php echo htmlspecialchars($photo['file_path']); ?>" alt="Event Photo" loading="lazy">
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <div id="lightbox">
        <span class="close-btn" onclick="closeLightbox()">&times;</span>
        <div class="nav-btn prev-btn" onclick="prevPhoto()">&lsaquo;</div>
        <img id="lightbox-img" src="" alt="Zoomed Photo">
        <div class="nav-btn next-btn" onclick="nextPhoto()">&rsaquo;</div>
    </div>

    <footer style="padding: 5rem 0; text-align: center; color: var(--text-muted); border-top: 1px solid var(--glass-border); margin-top: 5rem;">
        <p>&copy; 2026 College Photography Club. Inspired by visionaries.</p>
    </footer>

    <script src="assets/js/main.js"></script>
    <script>
        const photos = <?php echo json_encode(array_column($photos, 'file_path')); ?>;
        let currentIndex = 0;

        function openLightbox(index) {
            currentIndex = index;
            updateLightbox();
            document.getElementById('lightbox').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            document.getElementById('lightbox').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function updateLightbox() {
            document.getElementById('lightbox-img').src = photos[currentIndex];
        }

        function nextPhoto() {
            currentIndex = (currentIndex + 1) % photos.length;
            updateLightbox();
        }

        function prevPhoto() {
            currentIndex = (currentIndex - 1 + photos.length) % photos.length;
            updateLightbox();
        }

        // Keyboard support
        document.addEventListener('keydown', (e) => {
            if (document.getElementById('lightbox').style.display === 'flex') {
                if (e.key === 'ArrowRight') nextPhoto();
                if (e.key === 'ArrowLeft') prevPhoto();
                if (e.key === 'Escape') closeLightbox();
            }
        });
    </script>
</body>
</html>
