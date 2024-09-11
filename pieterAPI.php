<?php

/**
 * API for the Pieter Adriaans website to connect to the database and send back JSON data
 * 
 */

include 'functions.php';
// Connect to MySQL
$pdo = pdo_connect_mysql();


if (isset($_SERVER['HTTP_ORIGIN'])) {
  // You can decide whether to allow the 'origin' in the 'Access-Control-Allow-Origin' header
  // Replace '*' with the specific website you want to allow or keep it '*' to allow all websites
  // header("Access-Control-Allow-Origin: https://azart.codette.net");
  header("Access-Control-Allow-Origin: *");
  header('Access-Control-Allow-Credentials: true');
  header('Access-Control-Max-Age: 86400');    // cache for 1 day
}
// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
    header("Access-Control-Allow-Methods: GET, OPTIONS");

  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
    header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

  exit(0);
}


// check the request method
$method = $_SERVER['REQUEST_METHOD'];

// sanitize the input for id (int) and category (int) (modern php) 
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? null;
$category = filter_input(INPUT_GET, 'category', FILTER_VALIDATE_INT) ?? null;
$categories = filter_input(INPUT_GET, 'categories', FILTER_VALIDATE_INT) ?? null;


if ($method == 'GET') {
  // get all for sale categories
  if (isset($categories)) {
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE is_for_sale = 1');
    $stmt->execute();
    $categoriesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($categoriesData);
    exit();
  }


  if (isset($id)) {
    // Return the media with the provided id
    $stmt = $pdo->prepare('SELECT * FROM media WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $media = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($media);
    exit();
  }

  if (isset($category)) {
    if ($category == 0) {
      // Retrieve the media
      // Return the media with the provided category
      $stmt = $pdo->prepare("SELECT * FROM media WHERE art_status = 'available' ORDER BY art_price DESC,uploaded_date ASC");
      $stmt->execute();
      $media = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
      // Return the media with the provided category
      $stmt = $pdo->prepare("SELECT m.* FROM media m JOIN media_categories mc ON mc.media_id = m.id WHERE mc.category_id = ? ORDER BY m.art_price DESC");
      $stmt->execute([$_GET['category']]);

      $media = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (!$media) {
        echo json_encode(['message' => 'No media found']);
        exit();
      } else {
        echo json_encode($media);
        exit();
      }
    }

    echo json_encode($media);
  } else {
    echo json_encode(['message' => 'Invalid request method']);
  }

  // Close the connection
  $pdo = null;
}
