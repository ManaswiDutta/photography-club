<?php
require_once '../includes/db.php';
require_once 'auth.php';
checkLogin();

// Increase limits for large folder or link imports
ini_set('max_execution_time', '0'); 
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

    if (isset($_FILES['folder_files']) || isset($_POST['google_link'])) {
        $event_name = $_POST['event_name'] ?? '';
        $event_date = $_POST['event_date'] ?? ''; 
        $description = $_POST['description'] ?? '';
        $upload_type = $_POST['upload_type'] ?? 'folder';

        if ($upload_type === 'link') {
            $google_link = $_POST['google_link'] ?? '';
            if (empty($google_link)) {
                sendResponse('error', 'Google Photos link is required.');
            }

            try {
                // Call the python script in single URL mode
                $cmd = "python " . escapeshellarg(__DIR__ . "/../scripts/import_bulk.py") . " " . escapeshellarg($google_link) . " 2>&1";
                exec($cmd, $output, $return_val);

                if ($return_val === 0) {
                    sendResponse('success', 'Google Photos album linked and ingested successfully.');
                } else {
                    $error_msg = implode("\n", $output);
                    sendResponse('error', 'Import failed: ' . $error_msg);
                }
            } catch (Exception $e) {
                sendResponse('error', 'System error: ' . $e->getMessage());
            }
        } else {
            // Existing Folder Upload Logic
            if (!$event_name || !$event_date) {
                sendResponse('error', 'Event Name and Date are required.');
            }

            // Create folder name: event_name_month_year
            $date_obj = DateTime::createFromFormat('Y-m', $event_date);
            $folder_suffix = $date_obj ? $date_obj->format('M_Y') : 'Unknown';
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

                if (isset($_FILES['folder_files']['name'])) {
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
                if ($pdo->inTransaction()) $pdo->rollBack();
                sendResponse('error', $e->getMessage());
                $message = "Error: " . $e->getMessage();
            }
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
    <style>
        .btn-tab {
            opacity: 0.6;
            background: transparent;
            color: white;
            transition: 0.3s;
        }
        .btn-tab.active {
            opacity: 1;
            background: var(--primary) !important;
            color: white;
        }

        /* Indeterminate Progress Animation */
        @keyframes indeterminate {
            0% { left: -35%; right: 100%; }
            60% { left: 100%; right: -90%; }
            100% { left: 100%; right: -90%; }
        }
        .progress-indeterminate {
            position: relative;
            overflow: hidden;
        }
        .progress-indeterminate::after {
            content: '';
            position: absolute;
            top: 0; bottom: 0;
            background-color: var(--primary);
            animation: indeterminate 2s infinite linear;
            width: 50%;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

    <main class="container animate-up" style="padding-top: 10rem;">
        <div class="glass-card upload-card" style="margin: 0 auto; max-width: 600px;">
            <div style="margin-bottom: 3rem;">
                <h2 style="margin-bottom: 0.5rem; font-size: 2.5rem;">Upload Memories</h2>
                <p style="color: var(--text-muted);">Choose your source: bulk-upload a folder or link a Google Photos album.</p>
            </div>

            <!-- Upload Type Toggle -->
            <div style="display: flex; gap: 1rem; margin-bottom: 2.5rem; background: rgba(0,0,0,0.2); padding: 0.5rem; border-radius: 12px; border: 1px solid var(--glass-border);">
                <button type="button" class="btn btn-tab active" data-tab="folder" onclick="switchMode('folder')" style="flex: 1; border: none; padding: 0.8rem;">Local Folder</button>
                <button type="button" class="btn btn-tab" data-tab="link" onclick="switchMode('link')" style="flex: 1; border: none; padding: 0.8rem;">Google Photos</button>
            </div>
            
            <?php if ($message): ?>
                <div class="glass" style="padding: 1rem; border-color: var(--primary); margin-bottom: 2rem;">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form id="uploadForm" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1.5rem;">
                <input type="hidden" name="upload_type" id="upload_type" value="folder">
                
                <!-- Common Metadata -->
                <div id="metadata-fields">
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Event Name</label>
                        <input type="text" name="event_name" id="event_name" required style="width: 100%; padding: 0.8rem; background: var(--bg-card); border: 1px solid var(--glass-border); border-radius: 8px; color: white;" placeholder="e.g. Annual Fest 2026">
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Event Month & Year</label>
                        <input type="month" name="event_date" id="event_date" required style="width: 100%; padding: 0.8rem; background: var(--bg-card); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Description</label>
                        <textarea name="description" id="description" rows="3" style="width: 100%; padding: 0.8rem; background: var(--bg-card); border: 1px solid var(--glass-border); border-radius: 8px; color: white;" placeholder="Brief details about the event..."></textarea>
                    </div>
                </div>

                <!-- Folder Mode Specific -->
                <div id="folder-mode-fields">
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Select Event Folder</label>
                    <input type="file" name="folder_files[]" id="folder_files" webkitdirectory mozdirectory directory multiple required style="width: 100%; padding: 0.8rem; background: var(--bg-card); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem;">Note: This will upload all images inside the selected folder.</p>
                </div>

                <!-- Link Mode Specific -->
                <div id="link-mode-fields" style="display: none;">
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Album Link</label>
                    <input type="url" name="google_link" id="google_link" style="width: 100%; padding: 0.8rem; background: var(--bg-card); border: 1px solid var(--glass-border); border-radius: 8px; color: white;" placeholder="https://photos.app.goo.gl/...">
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem;">The system will automatically download and ingest images from this album.</p>
                    <p style="font-size: 0.8rem; color: var(--primary); margin-top: 0.5rem; font-weight: 600;">⚠️ Import might take 30-60 seconds depending on album size.</p>
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

                <button type="submit" id="submitBtn" class="btn btn-primary" style="margin-top: 1rem;">Start Processing</button>
            </form>
        </div>
    </main>

    <script>
        function switchMode(mode) {
            document.getElementById('upload_type').value = mode;
            
            const folderFields = document.getElementById('folder-mode-fields');
            const linkFields = document.getElementById('link-mode-fields');
            const metaFields = document.getElementById('metadata-fields');
            const folderBtn = document.querySelector('[data-tab="folder"]');
            const linkBtn = document.querySelector('[data-tab="link"]');
            const submitBtn = document.getElementById('submitBtn');
            const eventName = document.getElementById('event_name');
            const eventDate = document.getElementById('event_date');
            
            if (mode === 'folder') {
                folderFields.style.display = 'block';
                linkFields.style.display = 'none';
                metaFields.style.display = 'block';
                folderBtn.classList.add('active');
                linkBtn.classList.remove('active');
                
                document.getElementById('folder_files').required = true;
                document.getElementById('google_link').required = false;
                eventName.required = true;
                eventDate.required = true;
                submitBtn.innerText = 'Start Upload';
            } else {
                folderFields.style.display = 'none';
                linkFields.style.display = 'block';
                metaFields.style.display = 'none'; 
                folderBtn.classList.remove('active');
                linkBtn.classList.add('active');
                
                document.getElementById('folder_files').required = false;
                document.getElementById('google_link').required = true;
                eventName.required = false;
                eventDate.required = false;
                submitBtn.innerText = 'Import Album';
            }
        }

        document.getElementById('uploadForm').onsubmit = function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const xhr = new XMLHttpRequest();
            const submitBtn = document.getElementById('submitBtn');
            const progressContainer = document.getElementById('progressContainer');
            const progressBar = document.getElementById('progressBar');
            const percentText = document.getElementById('percentText');
            const statusText = document.getElementById('statusText');
            const uploadType = document.getElementById('upload_type').value;

            progressContainer.style.display = 'block';
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.5';

            if (uploadType === 'link') {
                statusText.innerText = 'Extracting album data...';
                percentText.innerText = 'Wait';
                progressBar.classList.add('progress-indeterminate');
                submitBtn.innerText = 'Importing...';
                
                // Change status after some time to reassure user
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        statusText.innerText = 'Downloading high-res photos...';
                    }
                }, 4000);
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        statusText.innerText = 'Finalizing with database...';
                    }
                }, 45000);
            } else {
                submitBtn.innerText = 'Uploading...';
            }

            xhr.upload.onprogress = function(event) {
                if (event.lengthComputable && uploadType === 'folder') {
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
                        alert('Error: ' + response.message);
                        resetButton();
                    }
                } catch (e) {
                    console.error('Raw response:', xhr.responseText);
                    alert('Server Error: Import failed or timed out. Check your PHP settings.');
                    resetButton();
                }
            };

            xhr.onerror = function() {
                alert('An error occurred during the process.');
                resetButton();
            };

            function resetButton() {
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.innerText = uploadType === 'folder' ? 'Start Upload' : 'Import Album';
                progressContainer.style.display = 'none';
                progressBar.classList.remove('progress-indeterminate');
                progressBar.style.width = '0%';
            }

            xhr.open('POST', 'upload_event.php', true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.send(formData);
        };
    </script>
<?php include 'footer.php'; ?>
