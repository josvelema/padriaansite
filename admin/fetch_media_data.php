<?php
require 'main.php';

if (isset($_GET['mediaId'])) {
    $mediaId = $_GET['mediaId'];

    $stmt = $pdo->prepare('SELECT * FROM media WHERE id = ?');
    $stmt->execute([$mediaId]);
    $mediaData = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($mediaData);
}
