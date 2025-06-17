<?php

declare(strict_types=1);

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use App\QR\Image\QRImageWithLogo;
use App\QR\Options\LogoOptions;

require_once('../vendor/autoload.php');
include 'main.php';

if (isset($_GET['category_id'])) {
  $category_id = (int)$_GET['category_id'];


  // Haal de categorie op
  $stmt = $pdo->prepare('SELECT id, media_type,title FROM categories WHERE id = ?');
  $stmt->execute([$category_id]);
  $category = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$category) {
    http_response_code(404);
    echo "Category not found.";
    exit;
  }

  // Bepaal doel-URL op basis van media_type
  $url = '';
  if ($category['media_type'] == 1) {
    $url = "https://www.pieter-adriaans.com/music?album=" . $category_id;
  } elseif ($category['media_type'] == 0) {
    $url = "https://www.pieter-adriaans.com/gallery?category=" . $category_id;
  } else {
    echo "QR code generation not supported for this media type.";
    exit;
  }

  $catTitle = $category['title'];
  $catTitle = str_replace(' ', '-', strtolower($catTitle));

  $options = new LogoOptions([
    'eccLevel' => QRCode::ECC_H,
    'imageBase64' => true,
    'outputType' => QRCode::OUTPUT_IMAGE_JPG,
    'logoSpaceHeight' => 12,
    'logoSpaceWidth' => 12,
    'scale' => 3,
    'version' => 7,
  ]);

  $qrOutput = new QRImageWithLogo(
    $options,
    (new QRCode($options))->getMatrix($url)
  );

  $qr_code = $qrOutput->dump(null, __DIR__ . '/favicon-32x32.png');

  $folder = '../media/qrcodes/';
  $filename = 'cat-' . $catTitle . '-' . $category_id . '-' . date('Ymd') . '.jpg';
  $fullpath = $folder . $filename;
  $publicPath = '/media/qrcodes/' . $filename;

  file_put_contents($fullpath, base64_decode(explode(',', $qr_code)[1]));


  $stmt = $pdo->prepare('UPDATE categories SET qr_url = ? WHERE id = ?');
  $stmt->execute([$publicPath, $category_id]);

  echo $publicPath;
}
