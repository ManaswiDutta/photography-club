<?php require_once 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PH - College Photography Club</title>
    <link rel="stylesheet" href="assets/css/style.css?v=2.1">
</head>
<body>
    <header>
        <div class="container">
            <a href="index.php" class="logo"><span>PHOTOGRAPHY </span>CLUB</a>
            <nav>
                <ul>
                    <li><a href="index.php" class="active">Home</a></li>
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

    <?php
    // Fetch Dynamic Backgrounds - Safety wrap in case table isn't created yet
    $dynamic_bgs = [];
    try {
        $bg_stmt = $pdo->query("SELECT p.file_path FROM backgrounds b JOIN photos p ON b.photo_id = p.id ORDER BY b.added_at DESC");
        if ($bg_stmt) {
            $dynamic_bgs = $bg_stmt->fetchAll();
        }
    } catch (PDOException $e) {
        // Table doesn't exist or other error, fallback to defaults
        $dynamic_bgs = [];
    }
    ?>
    <div class="bg-scroller">
        <?php if (!empty($dynamic_bgs)): ?>
            <?php foreach ($dynamic_bgs as $index => $bg): ?>
                <div class="bg-slide <?php echo $index === 0 ? 'active' : ''; ?>" style="background-image: url('<?php echo htmlspecialchars($bg['file_path']); ?>')"></div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="bg-slide active" style="background-image: url('https://images.unsplash.com/photo-1493238792040-d710d73aa2a3?auto=format&fit=crop&q=80&w=1920')"></div>
            <div class="bg-slide" style="background-image: url('https://images.unsplash.com/photo-1452587925148-ce544e77e70d?auto=format&fit=crop&q=80&w=1920')"></div>
            <div class="bg-slide" style="background-image: url('https://images.unsplash.com/photo-1542038784456-1ea8e935640e?auto=format&fit=crop&q=80&w=1920')"></div>
        <?php endif; ?>
    </div>
    <div class="bg-overlay"></div>
    <div class="bg-grain"></div>

    <main>
        <section class="hero">
            <div class="container">
                <span class="hero-accent reveal">The Visual Narrative</span>
                <h1 class="reveal">PH. CLUB<br>COLLECTIVE</h1>
                <div class="reveal" style="max-width: 500px; margin-top: 4rem;">
                    <p style="font-size: 1.1rem; color: var(--text-muted); margin-bottom: 3rem;">
                        We capture the invisible moments. A collective of students dedicated to the art of light, shadow, and the albums they tell.
                    </p>
                    <div style="display: flex; gap: 1.5rem;">
                        <a href="#events" class="btn btn-primary">Our albums</a>
                        <a href="gallery.php" class="btn btn-outline">Gallery</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- <div class="marquee-container">
            <div class="marquee-content">
                <span>Capture the unknown</span>
                <span>Fostering Vision</span>
                <span>The Editorial Collective</span>
                <span>Capture the unknown</span>
                <span>Fostering Vision</span>
                <span>The Editorial Collective</span>
            </div>
        </div> -->

        <section class="container" style="padding-bottom: 5rem;">
            <!-- <div class="stats-grid reveal">
                <div class="stat-item">
                    <div class="stat-number" data-value="120">0</div>
                    <div class="stat-label">Active Members</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-value="45">0</div>
                    <div class="stat-label">Successful Events</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-value="8500">0</div>
                    <div class="stat-label">Photos Archived</div>
                </div>
            </div> -->

            <div style="display: flex; justify-content: space-between; align-items: center; gap: 2rem; margin-bottom: 8rem;" class="reveal">
                <div>
                    <!-- <span style="color: var(--primary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; display: block; margin-bottom: 1rem;">Portfolio</span> -->
                    <h2 class="section-title">Selected<br>albums</h2>
                </div>
                <div style="max-width: 400px; padding-bottom: 1rem;">
                    <p style="color: var(--text-muted); font-size: 1.1rem;">A curation of our community's most impactful visual contributions to the campus history.</p>
                </div>
            </div>

            <div id="events" class="event-grid">
                <?php
                $stmt = $pdo->query("SELECT * FROM events ORDER BY event_date DESC LIMIT 4");
                $events = $stmt->fetchAll();
                
                if (empty($events)): ?>
                    <p style="grid-column: 1/-1; color: var(--text-muted); font-size: 1.5rem; border-top: 1px solid var(--glass-border); padding-top: 2rem;">Coming soon...</p>
                <?php else: 
                    foreach ($events as $event): ?>
                        <div class="event-card reveal">
                            <img src="<?php echo htmlspecialchars($event['cover_image']); ?>" class="event-img" alt="Event Cover">
                            <div class="event-overlay">
                                <span style="font-size: 0.8rem; color: var(--primary); font-weight: 700; margin-bottom: 1rem;"><?php echo date('F Y', strtotime($event['event_date'])); ?></span>
                                <h3 style="font-size: 2.5rem; margin-bottom: 1.5rem;"><?php echo htmlspecialchars($event['name']); ?></h3>
                                <a href="event.php?id=<?php echo $event['id']; ?>" class="btn btn-outline" style="width: fit-content; padding: 1rem 2rem;">See Album</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div style="text-align: center; margin-top: 10rem;" class="reveal">
                <a href="gallery.php" style="font-size: 5rem; font-family: 'Outfit'; font-weight: 900; opacity: 0.9; transition: opacity 0.4s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.3">
                    ALL EVENTS &rarr;
                </a>
            </div>
        </section>
    </main>

    <footer>
        <div class="container footer-content">
            <div class="logo"><span>PHOTOGRAPHy </span>CLUB</div>
            
            <?php
            // Fetch Social Links
            $social_stmt = $pdo->query("SELECT * FROM social_links");
            $social_links = $social_stmt->fetchAll();
            
            if (!empty($social_links)): ?>
                <div class="social-links">
                    <?php foreach ($social_links as $link): 
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
                        <a href="<?php echo htmlspecialchars($link['url']); ?>" target="_blank" class="social-icon" title="<?php echo htmlspecialchars($link['platform']); ?>">
                            <i class="<?php echo $icon; ?>"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <p class="copyright">&copy;2026 Vidyamandira Photography Club</p>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>
