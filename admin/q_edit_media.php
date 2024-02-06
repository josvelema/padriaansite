<?php
header('Content-Type: application/json');

include_once '../config.php';
include_once '../functions.php';

$pdo = pdo_connect_mysql();

$data = json_decode(file_get_contents('php://input'), true);
$id = (int) $data['id'];
$newTitle = $data['newTitle'];

if (isset($data['id']) && is_int($data['id'])) {
  // Sanitize and validate input data
  $title = htmlspecialchars($data['newTitle'], ENT_QUOTES, 'UTF-8');   // Sanitize and validate input data
  if ($title) {
    $stmt = $pdo->prepare('UPDATE media SET title = ? WHERE id = ?');
    $stmt->bindParam(1, $title, PDO::PARAM_STR);
    $stmt->bindParam(2, $id, PDO::PARAM_INT);
    $stmt->execute();
  } else {
    echo "Title is not valid";
  }
} else {
  echo "Unvalid data ID";
}
// Sending back a sample response
echo json_encode(['success' => true,
'id' => $data['id'] ,
'newTitle' => $title
]);
