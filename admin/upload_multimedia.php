<?php
require 'main.php';

if (!isset($_POST['media_id']) || !isset($_POST['type']) || !isset($_FILES['file'])) {
    http_response_code(400);
    exit('Invalid request.');
}

$media_id = $_POST['media_id'];
$type = $_POST['type'];
$file = $_FILES['file'];

// Validate the file type
if ($type === 'audio') {
    $allowed_extensions = ['mp3', 'wav'];
} elseif ($type === 'video') {
    $allowed_extensions = ['mp4', 'webm'];
} else {
    http_response_code(400);
    exit('Invalid file type.');
}

$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if (!in_array($extension, $allowed_extensions)) {
    http_response_code(400);
    exit('Invalid file extension.');
}

// Upload the file to the appropriate folder
$upload_folder = '../media/multimedia/' . $type . '/';
$file_name = time() . '_' . $media_id . '.' . $extension;
$target_path = $upload_folder . $file_name;

if (!move_uploaded_file($file['tmp_name'], $target_path)) {
    http_response_code(500);
    exit('Error uploading file.');
}

// Update the media entry in the database
$column = $type === 'audio' ? 'audio_url' : 'video_url';
$stmt = $pdo->prepare("UPDATE media SET {$column} = ? WHERE id = ?");
$stmt->execute([$file_name, $media_id]);

echo 'File uploaded successfully.';
?>
