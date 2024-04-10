<?php

// Directory containing images
$directory = 'images/';

// Directory to save thumbnails
$thumbnailDirectory = 'thumbnails/';

// Get all files in the directory
$files = scandir($directory);

// Loop through each file
foreach ($files as $file) {
    // Skip if it's not a valid image file
    $imagePath = $directory . $file;
    if (!is_file($imagePath) || !in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), array('jpg', 'jpeg', 'png', 'gif'))) {
        continue;
    }

    // Get image size
    list($width, $height) = getimagesize($imagePath);

    // Calculate new dimensions
    $newWidth = 320;
    $newHeight = 180;
    $aspectRatio = $width / $height;
    $newAspectRatio = $newWidth / $newHeight;
    if ($aspectRatio > $newAspectRatio) {
        $newHeight = $newWidth / $aspectRatio;
    } else {
        $newWidth = $newHeight * $aspectRatio;
    }

    // Create a new true color image
    $newImage = imagecreatetruecolor($newWidth, $newHeight);

    // Get the image type
    $mime = mime_content_type($imagePath);

    // Create the image based on the type
    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($imagePath);
            break;
        case 'image/png':
            $image = imagecreatefrompng($imagePath);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($imagePath);
            break;
        default:
            continue;
    }

    // Resize the image
    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    // Save the image to a file
    $thumbnailPath = $thumbnailDirectory . pathinfo($file, PATHINFO_FILENAME) . '_thumb.' . pathinfo($file, PATHINFO_EXTENSION);
    switch ($mime) {
        case 'image/jpeg':
            imagejpeg($newImage, $thumbnailPath);
            break;
        case 'image/png':
            imagepng($newImage, $thumbnailPath);
            break;
        case 'image/gif':
            imagegif($newImage, $thumbnailPath);
            break;
    }

    // Free up memory
    imagedestroy($image);
    imagedestroy($newImage);

    echo 'Thumbnail for ' . $file . ' generated successfully!<br>';
}

?>
