<?php
require_once '../includes/db.php';
require_once 'auth.php';
checkLogin();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Get folder name to delete files
    $stmt = $pdo->prepare("SELECT folder_name FROM events WHERE id = ?");
    $stmt->execute([$id]);
    $event = $stmt->fetch();
    
    if ($event) {
        $folder_path = '../uploads/' . $event['folder_name'];
        
        // Delete folder recursively
        if (is_dir($folder_path)) {
            $files = array_diff(scandir($folder_path), array('.', '..'));
            foreach ($files as $file) {
                unlink($folder_path . '/' . $file);
            }
            rmdir($folder_path);
        }
        
        // Delete records
        $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
        $stmt->execute([$id]);
    }
}

header("Location: dashboard.php");
exit();
?>
