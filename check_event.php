<?php
require_once 'includes/db.php';
$id = 17;
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if ($event) {
    echo "Found Event ID 17:\n";
    print_r($event);
} else {
    echo "Event ID 17 NOT FOUND.\n";
    echo "Latest 5 events:\n";
    $stmt = $pdo->query("SELECT id, name, event_date FROM events ORDER BY id DESC LIMIT 5");
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
}
?>
