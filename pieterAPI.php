<?php

/**
 * API for the Pieter Adriaans website to connect to the database and send back JSON data
 * 
 */

include 'functions.php';
// Connect to MySQL
$pdo = pdo_connect_mysql();

// check the request method
$method = $_SERVER['REQUEST_METHOD'];

// if the request is a GET request return all media

if ($method == 'GET') {
  if (isset($_GET['id'])) {
    // Return the media with the provided id
    $stmt = $pdo->prepare('SELECT * FROM media WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $media = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($media);
    exit();
  }
  if (isset($_GET['category']) && is_int($_GET['category'])) {
    if ($_GET['category'] == 0) {
      // Retrieve the media
      $stmt = $pdo->prepare('SELECT * FROM media ORDER BY id DESC');
      $stmt->execute();
      $media = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
      // Return the media with the provided category
      $stmt = $pdo->prepare('SELECT * FROM media WHERE category = ?');
      $stmt->execute([$_GET['category']]);
      $media = $stmt->fetchAll(PDO::FETCH_ASSOC);
      echo json_encode($media);
      exit();
    }
  }

  echo json_encode($media);
} else {
  echo json_encode(['message' => 'Invalid request method']);
}
