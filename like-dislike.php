<?php
include 'functions.php';
// Connect to MySQL
$pdo = pdo_connect_mysql();
// Check if the ID is specified in the URL
if (!isset($_GET['id'], $_GET['type'])) {
    // Stop execution and output error
    exit('Invalid ID!');
}
// Check if user has already liked/disliked the corresponding media
if (isset($_COOKIE['like_dislike_' . $_GET['id']])) {
    exit('You have already liked/disliked the media!');
}
// User liked the media
if ($_GET['type'] == 'like') {
    // Create a new cookie
    setcookie('like_dislike_' . $_GET['id'], 'liked', 2147483647);
    // Increment the likes in the database
    $stmt = $pdo->prepare('UPDATE media SET likes = likes + 1 WHERE id = ?');
    $stmt->execute([ $_GET['id'] ]);
    exit('success');
}
// User disliked the media
if ($_GET['type'] == 'dislike') {
    // Create a new cookie
    setcookie('like_dislike_' . $_GET['id'], 'disliked', 2147483647);
    // Decrement the likes in the database
    $stmt = $pdo->prepare('UPDATE media SET dislikes = dislikes + 1 WHERE id = ?');
    $stmt->execute([ $_GET['id'] ]);
    exit('success');
}
?>