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
    <meta name="referrer" content="no-referrer">
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
    <?php include 'includes/header.php'; ?>

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
        
        <div style="position: relative; text-align: center;">
            <img id="lightbox-img" src="" alt="Zoomed Photo">
            <a id="external-link" href="" target="_blank" class="btn btn-outline" style="position: absolute; bottom: -4rem; left: 50%; transform: translateX(-50%); display: none; background: rgba(0,0,0,0.5); backdrop-filter: blur(10px); color: white; border-color: rgba(255,255,255,0.2); white-space: nowrap;">
                <i class="fab fa-google-drive" style="margin-right: 0.5rem;"></i> View on Google Photos
            </a>
        </div>

        <div class="nav-btn next-btn" onclick="nextPhoto()">&rsaquo;</div>
    </div>

<?php include 'includes/footer.php'; ?>

    <script src="assets/js/main.js"></script>
    <script>
        const photos = <?php echo json_encode($photos); ?>;
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
            const photo = photos[currentIndex];
            const img = document.getElementById('lightbox-img');
            const link = document.getElementById('external-link');
            
            img.src = photo.file_path;
            
            if (photo.external_link) {
                link.href = photo.external_link;
                link.style.display = 'block';
            } else {
                link.style.display = 'none';
            }
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
