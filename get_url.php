<?php
require_once 'includes/db.php';
$stmt = $pdo->prepare('SELECT file_path FROM photos WHERE event_id = 17 LIMIT 1');
$stmt->execute();
echo $stmt->fetchColumn();
?>
