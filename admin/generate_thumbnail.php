<?php
// check if image_path is set
if (!isset($_GET['image_path'])) {
    die('Invalid request');
}

$original_image = $_GET['image_path'];

// check if the file exists and if it is an image
if (!file_exists($original_image) || !getimagesize($original_image)) {
    die('Invalid image');
}

// get the image size
list($width, $height) = getimagesize($original_image);

$new_width = 320;
$new_height = 180;

// maintain aspect ratio
$aspect_ratio = $width / $height;
$new_aspect_ratio = $new_width / $new_height;

if ($aspect_ratio > $new_aspect_ratio) {
    $new_height = $new_width / $aspect_ratio;
} else {
    $new_width = $new_height * $aspect_ratio;
}

// create a new true color image
$new_image = imagecreatetruecolor($new_width, $new_height);

// get the image type
$image_info = getimagesize($original_image);
$mime = $image_info['mime'];

// create the image based on the type
switch ($mime) {
    case 'image/jpeg':
        $image = imagecreatefromjpeg($original_image);
        break;
    case 'image/png':
        $image = imagecreatefrompng($original_image);
        break;
    case 'image/gif':
        $image = imagecreatefromgif($original_image);
        break;
    default:
        die('Invalid image type');
}

// resize the image
imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

// save the image to a file
$new_image_path = 'thumbnails/' . basename($original_image);

// Output the image to browser or save to file
switch ($mime) {
    case 'image/jpeg':
        imagejpeg($new_image, $new_image_path);
        break;
    case 'image/png':
        imagepng($new_image, $new_image_path);
        break;
    case 'image/gif':
        imagegif($new_image, $new_image_path);
        break;
}

// Free up memory
imagedestroy($image);
imagedestroy($new_image);

echo 'Thumbnail generated successfully!';
?>
