<?php
include 'functions.php';

// Connect to MySQL
$pdo = pdo_connect_mysql();

// Your existing logic for fetching media goes here
// ...

// Return media data as JSON
header('Content-Type: application/json');
echo json_encode($media);
?>
