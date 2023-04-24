<?php

declare(strict_types=1);

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use App\QR\Image\QRImageWithLogo;
use App\QR\Options\LogoOptions;


require_once('vendor/autoload.php');

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
  (new QRCode($options))->getMatrix('https://pieter-adriaans.com/')
);

$qrcode = $qrOutputInterface->dump(
  null,
  __DIR__.'/public/img/favicon-32x32.png'
);

$file = __DIR__.'/public/img/qr-1338.png';


$imageData = base64_decode(substr($qrcode, strpos($qrcode, ',') + 1));
file_put_contents($file, $imageData);

// $file = __DIR__.'/public/img/qrcode.png';
// $gdImage = imagecreatefromstring(base64_decode($qrcode));
// imagepng($gdImage, $file);

?>
