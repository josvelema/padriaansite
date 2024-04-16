<?php
function generateThumb($image_path)
{
    $original_image = $image_path;

    // check if the file exists and if it is an image
    if (!file_exists($original_image) || !getimagesize($original_image)) {
        die('Invalid image');
    }

    // get the image size
    list($width, $height) = getimagesize($original_image);

    $new_width = 320;
    $new_height = 180;

    // calculate scaling factors for width and height
    $width_scale = $new_width / $width;
    $height_scale = $new_height / $height;

    // choose the smaller scaling factor to maintain aspect ratio
    $scale = min($width_scale, $height_scale);

    // calculate new dimensions
    $new_width = $width * $scale;
    $new_height = $height * $scale;

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

    // Fix orientation
    if ($mime == 'image/jpeg') {
        $exif = exif_read_data($original_image);
        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                    $image = imagerotate($image, 180, 0);
                    break;
                case 6:
                    $image = imagerotate($image, -90, 0);
                    break;
                case 8:
                    $image = imagerotate($image, 90, 0);
                    break;
            }
        }
    }

    // resize the image
    imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    // Crop the image
    $width = imagesx($new_image);
    $height = imagesy($new_image);
    $minSize = min($width, $height);
    $cropX = ($width - $minSize) / 2;
    $cropY = ($height - $minSize) / 2;
    $new_image = imagecrop($new_image, ['x' => $cropX, 'y' => $cropY, 'width' => $minSize, 'height' => $minSize]);
    
    // save the image to a file
    $new_image_path = '../media/thumbnails/' . basename($original_image);

    // db image path 
    $db_thumbnail_path = 'media/thumbnails/' . basename($original_image);

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

    return $db_thumbnail_path;
}
