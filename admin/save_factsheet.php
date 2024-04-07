<?php
require 'main.php';

if (isset($_POST['imageDataUrl']) && isset($_POST['mediaId'])) {
  $mediaId = $_POST['mediaId'];
  $imageDataUrl = $_POST['imageDataUrl'];

  $imageData = base64_decode(substr($imageDataUrl, strpos($imageDataUrl, ',') + 1));

  $filename = '../media/factsheets/' . date('Y-m-d') . '-' . $mediaId . '-card' . '.png';
  $filename_pub = 'media/factsheets/' . date('Y-m-d') . '-' . $mediaId .  '-card' . '.png';
  file_put_contents($filename, $imageData);

  // Update the 'media' table with the generated business card image URL
  $stmt = $pdo->prepare('UPDATE media SET factsheet_url = ? WHERE id = ?');
  $stmt->execute([$filename_pub, $mediaId]);
}
