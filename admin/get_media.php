<?php
include 'main.php';

if (!isset($_GET['media_id'])) {
    http_response_code(400);
    die('Invalid media_id parameter');
}

$media_id = $_GET['media_id'];

$stmt = $pdo->prepare('SELECT * FROM media WHERE id = ?');
$stmt->execute([$media_id]);
$media = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$media) {
    http_response_code(404);
    die('Media not found');
}

header('Content-Type: application/json');
echo json_encode($media);
?>
