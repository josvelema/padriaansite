<?php

declare(strict_types=1);

namespace App\QR\Options;

use chillerlan\QRCode\QROptions;

class LogoOptions extends QROptions
{
  protected int $logoSpaceWidth;
  protected int $logoSpaceHeight;
}
