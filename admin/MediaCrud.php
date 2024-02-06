<?php
include_once "../config.php";
include_once "../functions.php";
header('Content-Type: application/json');

$pdo = pdo_connect_mysql();
$data = json_decode(file_get_contents('php://input'), true);
$id = (int) $data['id'];

if (isset($data['id']) && is_int($data['id'])) {
  // id set and is valid read media from id
  $stmt = $pdo->prepare('SELECT * FROM media WHERE id = ?');


  // single result
  $media = $stmt->fetch(PDO::FETCH_ASSOC);

  // delete

  if ($media['thumbnail']) {
    unlink('../' . $media['thumbnail']);
  }
  unlink('../' . $media['filepath']);
  $stmt = $pdo->prepare('DELETE m, mc FROM media m LEFT JOIN media_categories mc ON mc.media_id = m.id WHERE m.id = ?');
  $stmt->bindParam(1, $id, PDO::PARAM_INT);
  $stmt->execute();
  // Sending back a sample response
  echo json_encode(['success' => true]);
}
