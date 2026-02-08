<?php require_once 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery | Photo Club</title>
    <link rel="stylesheet" href="assets/css/style.css?v=2.1">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="container animate-up" style="min-height: 80vh; padding-top: 8rem;">
        <div style="text-align: center; margin-bottom: 6rem;">
            <h1 style="font-size: 4.5rem; margin-bottom: 1rem;">The Compendium</h1>
            <p style="color: var(--text-muted); font-size: 1.2rem; font-weight: 300;">Browsing through our visual history.</p>
        </div>

        <!-- Filters -->
        <div class="glass" style="padding: 2rem; border-radius: 24px; margin-bottom: 4rem; display: flex; flex-wrap: wrap; gap: 1.5rem; align-items: flex-end;">
            <form method="GET" style="display: contents;">
                <div style="flex: 2; min-width: 250px;">
                    <label style="display: block; margin-bottom: 0.8rem; font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em;">Search Events</label>
                    <input type="text" name="search" placeholder="Event name..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                </div>
                
                <?php
                // Fetch dynamic years and their specific months from DB
                $year_month_data = [];
                $stmt = $pdo->query("SELECT DISTINCT YEAR(event_date) as yr, MONTH(event_date) as mo FROM events ORDER BY yr DESC, mo ASC");
                while ($row = $stmt->fetch()) {
                    $year_month_data[$row['yr']][] = [
                        'val' => $row['mo'],
                        'name' => date('F', mktime(0, 0, 0, $row['mo'], 1))
                    ];
                }
                
                $sel_year = $_GET['year'] ?? '';
                $sel_month = $_GET['month'] ?? '';
                ?>

                <div style="flex: 1; min-width: 120px;">
                    <label style="display: block; margin-bottom: 0.8rem; font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em;">Year</label>
                    <select name="year" id="year-select" onchange="updateMonths()">
                        <option value="">All Years</option>
                        <?php foreach (array_keys($year_month_data) as $y): ?>
                            <option value="<?php echo $y; ?>" <?php echo ($sel_year == $y) ? 'selected' : ''; ?>><?php echo $y; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="month-container" style="flex: 1; min-width: 120px; display: <?php echo $sel_year ? 'block' : 'none'; ?>;">
                    <label style="display: block; margin-bottom: 0.8rem; font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em;">Month</label>
                    <select name="month" id="month-select">
                        <option value="">All Months</option>
                        <!-- Populated via JS -->
                    </select>
                </div>

                <div style="display: flex; gap: 0.8rem;">
                    <button type="submit" class="btn btn-primary" style="padding: 0.9rem 1.5rem;">Filter</button>
                    <a href="gallery.php" class="btn btn-outline" style="padding: 0.9rem 1.5rem;">Reset</a>
                </div>
            </form>
        </div>

        <script>
            const yearMonthData = <?php echo json_encode($year_month_data); ?>;
            const initialMonth = "<?php echo $sel_month; ?>";

            function updateMonths() {
                const yearSelect = document.getElementById('year-select');
                const monthContainer = document.getElementById('month-container');
                const monthSelect = document.getElementById('month-select');
                const selectedYear = yearSelect.value;

                if (!selectedYear) {
                    monthContainer.style.display = 'none';
                    monthSelect.value = "";
                    return;
                }

                monthContainer.style.display = 'block';
                monthSelect.innerHTML = '<option value="">All Months</option>';

                if (yearMonthData[selectedYear]) {
                    yearMonthData[selectedYear].forEach(m => {
                        const opt = document.createElement('option');
                        opt.value = m.val;
                        opt.textContent = m.name;
                        if (m.val == initialMonth) opt.selected = true;
                        monthSelect.appendChild(opt);
                    });
                }
            }

            // Initialize on load
            window.onload = updateMonths;
        </script>

        <div class="event-grid" style="margin-bottom: 10rem;">
            <?php
            $search = $_GET['search'] ?? '';
            $year = $_GET['year'] ?? '';
            $month = $_GET['month'] ?? '';
            
            $sql = "SELECT * FROM events WHERE 1=1";
            $params = [];
            
            if ($search) {
                $sql .= " AND name LIKE ?";
                $params[] = "%$search%";
            }
            if ($year) {
                $sql .= " AND YEAR(event_date) = ?";
                $params[] = $year;
            }
            if ($month) {
                $sql .= " AND MONTH(event_date) = ?";
                $params[] = $month;
            }
            
            $sql .= " ORDER BY event_date DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $events = $stmt->fetchAll();
            
            if (empty($events)): ?>
                <div style="text-align: center; grid-column: 1/-1; padding: 5rem 0;">
                    <p style="color: var(--text-muted); font-size: 1.2rem;">No events match your criteria.</p>
                </div>
            <?php else: 
                foreach ($events as $event): ?>
                    <div class="glass-card animate-up" style="overflow: hidden;">
                        <div style="height: 250px; background: url('<?php echo htmlspecialchars($event['cover_image']); ?>') center/cover; position: relative;">
                            <div style="position: absolute; bottom: 1rem; left: 1.5rem;">
                                <span style="font-size: 0.7rem; background: var(--primary); padding: 0.3rem 0.7rem; border-radius: 100px; font-weight: 700;"><?php echo date('M Y', strtotime($event['event_date'])); ?></span>
                            </div>
                        </div>
                        <div style="padding: 1.8rem;">
                            <h3 style="margin-bottom: 0.8rem; font-size: 1.4rem;"><?php echo htmlspecialchars($event['name']); ?></h3>
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem; height: 2.7em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                <?php echo htmlspecialchars($event['description']); ?>
                            </p>
                            <a href="event.php?id=<?php echo $event['id']; ?>" class="btn btn-outline" style="width: 100%;">View Collection</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

<?php include 'includes/footer.php'; ?>
</body>
</html>
