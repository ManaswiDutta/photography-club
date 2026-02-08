<?php
require_once '../includes/db.php';
require_once 'auth.php';
checkLogin();

// Increase limits for large folder uploads
ini_set('max_execution_time', '600'); 
ini_set('memory_limit', '512M');

$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
$message = '';

function sendResponse($status, $message) {
    global $is_ajax;
    if ($is_ajax) {
        header('Content-Type: application/json');
        echo json_encode(['status' => $status, 'message' => $message]);
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the upload actually occurred (detect post_max_size hit)
    if (empty($_POST) && $_SERVER['CONTENT_LENGTH'] > 0) {
        sendResponse('error', 'The uploaded folder is too large for the server. Please increase post_max_size in php.ini.');
        $message = 'Error: Folder too large for server limits.';
    }

    if (isset($_FILES['folder_files'])) {
        $event_name = $_POST['event_name'] ?? '';
        $event_date = $_POST['event_date'] ?? ''; 
        $description = $_POST['description'] ?? '';
        
        if (!$event_name || !$event_date) {
            sendResponse('error', 'Event Name and Date are required.');
        }

        // Create folder name: event_name_month_year
        $date_obj = DateTime::createFromFormat('Y-m', $event_date);
        $folder_suffix = $date_obj->format('M_Y');
        $clean_event_name = preg_replace('/[^a-zA-Z0-9]/', '_', strtolower($event_name));
        $folder_name = $clean_event_name . "_" . $folder_suffix;
        
        $upload_dir = '../uploads/' . $folder_name . '/';
        
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO events (name, event_date, folder_name, description) VALUES (?, ?, ?, ?)");
            $stmt->execute([$event_name, $event_date . "-01", $folder_name, $description]);
            $event_id = $pdo->lastInsertId();

            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $photo_stmt = $pdo->prepare("INSERT INTO photos (event_id, file_path) VALUES (?, ?)");
            $first_photo = null;

            foreach ($_FILES['folder_files']['name'] as $key => $name) {
                $tmp_name = $_FILES['folder_files']['tmp_name'][$key];
                $type = $_FILES['folder_files']['type'][$key];
                
                if (in_array($type, $allowed_types) && $_FILES['folder_files']['error'][$key] === UPLOAD_ERR_OK) {
                    $target_file = $upload_dir . basename($name);
                    if (move_uploaded_file($tmp_name, $target_file)) {
                        $relative_path = 'uploads/' . $folder_name . '/' . basename($name);
                        $photo_stmt->execute([$event_id, $relative_path]);
                        if (!$first_photo) $first_photo = $relative_path;
                    }
                }
            }
            
            if ($first_photo) {
                $pdo->prepare("UPDATE events SET cover_image = ? WHERE id = ?")->execute([$first_photo, $event_id]);
            }

            $pdo->commit();
            
            if ($is_ajax) {
                sendResponse('success', 'Event uploaded successfully');
            } else {
                $_SESSION['success_msg'] = "Event and photos uploaded successfully!";
                header("Location: dashboard.php");
                exit();
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            sendResponse('error', $e->getMessage());
            $message = "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Event | Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=2.1">
</head>
<body>
    <header class="glass">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <a href="../index.php" class="logo">ADMIN<span>PANEL</span></a>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="upload_event.php" class="active">Upload</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container animate-up" style="padding-top: 10rem;">
        <div class="glass-card" style="max-width: 700px; margin: 0 auto; padding: 4rem;">
            <div style="margin-bottom: 3rem;">
                <h2 style="font-size: 2.5rem; margin-bottom: 0.5rem;">Upload Memories</h2>
                <p style="color: var(--text-muted);">Select a folder to bulk-upload photos to a new event.</p>
            </div>
            
            <?php if ($message): ?>
                <div class="glass" style="padding: 1rem; border-color: var(--primary); margin-bottom: 2rem;">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form id="uploadForm" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Event Name</label>
                    <input type="text" name="event_name" id="event_name" required style="width: 100%; padding: 0.8rem; background: var(--bg-card); border: 1px solid var(--glass-border); border-radius: 8px; color: white;" placeholder="e.g. Annual Fest 2026">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Event Month & Year</label>
                    <input type="month" name="event_date" id="event_date" required style="width: 100%; padding: 0.8rem; background: var(--bg-card); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Description</label>
                    <textarea name="description" id="description" rows="3" style="width: 100%; padding: 0.8rem; background: var(--bg-card); border: 1px solid var(--glass-border); border-radius: 8px; color: white;" placeholder="Brief details about the event..."></textarea>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Select Event Folder</label>
                    <input type="file" name="folder_files[]" id="folder_files" webkitdirectory mozdirectory directory multiple required style="width: 100%; padding: 0.8rem; background: var(--bg-card); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem;">Note: This will upload all images inside the selected folder.</p>
                </div>

                <!-- Progress Bar Container -->
                <div id="progressContainer" style="display: none; margin-top: 1rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.85rem;">
                        <span id="statusText" style="color: var(--primary);">Uploading...</span>
                        <span id="percentText" style="color: var(--text-muted);">0%</span>
                    </div>
                    <div style="width: 100%; height: 8px; background: var(--bg-card); border-radius: 10px; overflow: hidden; border: 1px solid var(--glass-border);">
                        <div id="progressBar" style="width: 0%; height: 100%; background: var(--primary); transition: width 0.3s ease;"></div>
                    </div>
                </div>

                <button type="submit" id="submitBtn" class="btn btn-primary" style="margin-top: 1rem;">Start Upload</button>
            </form>
        </div>
    </main>

    <script>
        document.getElementById('uploadForm').onsubmit = function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const xhr = new XMLHttpRequest();
            const submitBtn = document.getElementById('submitBtn');
            const progressContainer = document.getElementById('progressContainer');
            const progressBar = document.getElementById('progressBar');
            const percentText = document.getElementById('percentText');
            const statusText = document.getElementById('statusText');

            // Show progress bar
            progressContainer.style.display = 'block';
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.5';
            submitBtn.innerText = 'Uploading...';

            xhr.upload.onprogress = function(event) {
                if (event.lengthComputable) {
                    const percent = Math.round((event.loaded / event.total) * 100);
                    progressBar.style.width = percent + '%';
                    percentText.innerText = percent + '%';
                    
                    if (percent === 100) {
                        statusText.innerText = 'Processing on server...';
                    }
                }
            };

            xhr.onload = function() {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        window.location.href = 'dashboard.php';
                    } else {
                        alert('Upload Error: ' + response.message);
                        resetButton();
                    }
                } catch (e) {
                    console.error('Raw response:', xhr.responseText);
                    alert('Server Error: The upload may have exceeded post_max_size or max_file_uploads. Please check your PHP settings.');
                    resetButton();
                }
            };

            xhr.onerror = function() {
                alert('An error occurred during the upload process.');
                resetButton();
            };

            function resetButton() {
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.innerText = 'Start Upload';
                progressContainer.style.display = 'none';
            }

            xhr.open('POST', 'upload_event.php', true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.send(formData);
        };
    </script>
</body>
</html>
