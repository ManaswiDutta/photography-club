<?php
require_once 'includes/db.php';
$id = 17;
$stmt = $pdo->prepare("SELECT COUNT(*) FROM photos WHERE event_id = ?");
$stmt->execute([$id]);
$count = $stmt->fetchColumn();

echo "Event ID 17 has $count photos.\n";

if ($count > 0) {
    $stmt = $pdo->prepare("SELECT * FROM photos WHERE event_id = ? LIMIT 3");
    $stmt->execute([$id]);
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
}
?>
