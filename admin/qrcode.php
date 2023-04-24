<?php

declare(strict_types=1);

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use App\QR\Image\QRImageWithLogo;
use App\QR\Options\LogoOptions;

require_once('../vendor/autoload.php');
include 'main.php';

if (isset($_GET['media_id'])) {
    $media_id = (int)$_GET['media_id'];

    // You may need to update the following line with the appropriate URL or data related to the media_id.
    $data = 'https://www.pieter-adriaans.com/view.php?id=' . $media_id;

    $options = new LogoOptions(
        [
            'eccLevel' => QRCode::ECC_H,
            'imageBase64' => true,
            'outputType' => QRCode::OUTPUT_IMAGE_JPG,
            'logoSpaceHeight' => 12,
            'logoSpaceWidth' => 12,
            'scale' => 3,
            'version' => 7,
        ]
    );

    $qrOutputInterface = new QRImageWithLogo(
        $options,
        (new QRCode($options))->getMatrix($data)
    );

    $qr_code = $qrOutputInterface->dump(
        null,
        __DIR__.'/favicon-32x32.png'
    );

    // Save the QR code as a PNG image file
    $qrcodes_folder = '../media/qrcodes/';
    $qrcodes_folder_pub = '/media/qrcodes/';
    $filename = date('Y-m-d') . '-' . $media_id . '.png';
    $filepath = $qrcodes_folder . $filename;
    $filepath_pub = $qrcodes_folder_pub . $filename;
    $imageData = base64_decode(substr($qr_code, strpos($qr_code, ',') + 1));
    file_put_contents($filepath, $imageData);

    // Update the 'media' table with the QR code URL
    $stmt = $pdo->prepare("UPDATE media SET qr_url = ? WHERE id = ?");
    $stmt->execute([$filepath_pub, $media_id]);

    // Return the QR code string
    echo $filepath_pub;
}
?>
