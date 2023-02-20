<?php
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use App\QR\Image\QRImageWithLogo;
use App\QR\Options\LogoOptions;


require_once('vendor/autoload.php');

// Define the image URL and ID
$imageUrl = 'https://example.com/image.jpg';
$imageId = 123;

// Define the QR code options
$options = new QROptions([
    'version' => 7,
    'outputType' => QRCode::OUTPUT_IMAGE_PNG,
    'eccLevel' => QRCode::ECC_H,
    'imageBase64' => true,
]);

// Create a new QR code instance with the options
$qrCode = new QRCode($options);

// Set the data to encode as the image URL and ID
$qrCode->setText($imageUrl . ':' . $imageId);

// Create a QR code image with a logo
$qrOutputInterface = new QRImageWithLogo(
    $options,
    $qrCode->getMatrix(),
    'path/to/logo.png',
    17,
    17,
    20
);

// Dump the QR code image to a string
$qrcode = $qrOutputInterface->dump();

// Save the QR code image as a PNG file
$file = 'path/to/qr-codes/' . $imageId . '.png';
file_put_contents($file, $qrcode);

// Write the QR code references to the database
$qrImageUrl = '/path/to/qr-codes/' . $imageId . '.png';

// Here you can use your own database connection and write the data to the table
$pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
$stmt = $pdo->prepare('INSERT INTO qr_images (qr_image_url, qr_image_id) VALUES (?, ?)');
$stmt->execute([$qrImageUrl, $imageId]);
