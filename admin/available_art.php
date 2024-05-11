<?php
include_once '../config.php';
include_once '../functions.php';

if (!isset($_SESSION['admin_loggedin'])) {
    header('Location: login.php');
    exit;
}

$pdo = pdo_connect_mysql();

$

$params = [
  'category_id' => $viewCat,

];
// get category title and is_private
$stmt = $pdo->prepare('SELECT * FROM categories WHERE id = :category_id');
$stmt->execute(['category_id' => $viewCat]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);
$catTitle = $category['title'];
$catDesc = $category['description'];
$catPrivate = $category['is_private'];
// count query
$stmt = $pdo->prepare('SELECT COUNT(m.id) FROM media m JOIN media_categories mc ON mc.media_id = m.id AND mc.category_id = :category_id WHERE m.type = "image" AND (m.title LIKE :term1 OR m.description LIKE :term2)');
$stmt->execute($params);
$count = $stmt->fetchColumn();
if ($count > 0) {
  $params['show'] = (int)$show;
  $params['from'] = (int)$from;
  $stmt = $pdo->prepare('SELECT m.* FROM media m JOIN media_categories mc ON mc.media_id = m.id AND mc.category_id = :category_id WHERE m.type = "image" AND (m.title LIKE :term1 OR m.description LIKE :term2) ORDER BY ' . $order_by . ' ' . $order_sort . ' LIMIT :show OFFSET :from');

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
