<?php
include_once '../config.php';
include_once '../functions.php';

if (!isset($_SESSION['admin_loggedin'])) {
    header('Location: login.php');
    exit;
}

$pdo = pdo_connect_mysql();

$params = [
  'term1' => '%' . $term . '%',
  'term2' => '%' . $term . '%'
];

// count query 
$stmt = $pdo->prepare('SELECT COUNT(id) FROM media WHERE type = "image" AND art_status IS NOT NULL AND art_status <> "not for sale"');
$stmt->execute($params);
$count = $stmt->fetchColumn();
if ($count > 0) {
  $params['show'] = (int)$show;
  $params['from'] = (int)$from;

  $stmt = $pdo->prepare('SELECT * FROM media WHERE type = "image" AND art_status IS NOT NULL AND art_status <> "not for sale" ORDER BY id LIMIT :show OFFSET :from');
  foreach ($params as $key => &$value) {
    if ($key == 'show' || $key == 'from') {
      $stmt->bindParam($key, $value, PDO::PARAM_INT);
    } else {
      $stmt->bindParam($key, $value);
    }
  }
  $stmt->execute();
  $media = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


if ($count > $show) {
  $total_pages = ceil($count / $show);
  $current_page = ceil($from / $show) + 1;
} else {
  $total_pages = 1;
  $current_page = 1;
}
// get current get parameters into string
$get_params = $_GET;
