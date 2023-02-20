<?php

declare(strict_types=1);

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use App\QR\Image\QRImageWithLogo;
use App\QR\Options\LogoOptions;


require_once('./../vendor/autoload.php');

$options = new LogoOptions(
  [
    'eccLevel' => QRCode::ECC_H,
    'imageBase64' => true,
    'logoSpaceHeight' => 17,
    'logoSpaceWidth' => 17,
    'scale' => 20,
    'version' => 7,
  ]
);

$qrOutputInterface = new QRImageWithLogo(
  $options,
  (new QRCode($options))->getMatrix('https://twilio.com')
);

$qrcode = $qrOutputInterface->dump(
  null,
  __DIR__.'/../public/img/favicon-32x32.png'
);

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Create QR Codes in PHP</title>
  <link rel="stylesheet" href="/css/styles.min.css">
</head>
<body>
<h1>Creating QR Codes in PHP</h1>
<div class="container">
  <img src='<?= $qrcode ?>' alt='QR Code' width='200' height='200'>
</div>
</body>
</html>
