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