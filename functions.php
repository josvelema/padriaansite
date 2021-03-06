<?php
include_once 'config.php';
// Connect to MySQL database function
function pdo_connect_mysql()
{
    try {
        $pdo = new PDO('mysql:host=' . db_host . ';dbname=' . db_name . ';charset=utf8', db_user, db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $exception) {
        // If there is an error with the connection, stop the script and display the error.
        exit('Failed to connect to database!');
    }
    return $pdo;
}
// Convert filesize to a readable format
function convert_filesize($bytes, $precision = 2)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}
// Compress image function
function compress_image($source, $quality)
{
    $info = getimagesize($source);
    if ($info['mime'] == 'image/jpeg') {
        imagejpeg(imagecreatefromjpeg($source), $source, $quality);
    } else if ($info['mime'] == 'image/webp') {
        imagewebp(imagecreatefromwebp($source), $source, $quality);
    } else if ($info['mime'] == 'image/png') {
        $png_quality = 9 - floor($quality / 10);
        $png_quality = $png_quality < 0 ? 0 : $png_quality;
        $png_quality = $png_quality > 9 ? 9 : $png_quality;
        imagepng(imagecreatefrompng($source), $source, $png_quality);
    }
}
// Correct image orientation function
function correct_image_orientation($source)
{
    if (strpos(strtolower($source), '.jpg') == false && strpos(strtolower($source), '.jpeg') == false) return;
    $exif = exif_read_data($source);
    $info = getimagesize($source);
    if ($exif && isset($exif['Orientation'])) {
        if ($exif['Orientation'] && $exif['Orientation'] != 1) {
            if ($info['mime'] == 'image/jpeg') {
                $img = imagecreatefromjpeg($source);
            } else if ($info['mime'] == 'image/webp') {
                $img = imagecreatefromwebp($source);
            } else if ($info['mime'] == 'image/png') {
                $img = imagecreatefrompng($source);
            }
            $deg = 0;
            $deg = $exif['Orientation'] == 3 ? 180 : $deg;
            $deg = $exif['Orientation'] == 6 ? 90 : $deg;
            $deg = $exif['Orientation'] == 8 ? -90 : $deg;
            if ($deg) {
                $img = imagerotate($img, $deg, 0);
                if ($info['mime'] == 'image/jpeg') {
                    imagejpeg($img, $source);
                } else if ($info['mime'] == 'image/webp') {
                    imagewebp($img, $source);
                } else if ($info['mime'] == 'image/png') {
                    imagepng($img, $source);
                }
            }
        }
    }
}
// Resize image function
function resize_image($source, $max_width, $max_height)
{
    $info = getimagesize($source);
    $image_width = $info[0];
    $image_height = $info[1];
    $new_width = $image_width;
    $new_height = $image_height;
    if ($image_width > $max_width || $image_height > $max_height) {
        if ($image_width > $image_height) {
            $new_height = floor(($image_height / $image_width) * $max_width);
            $new_width  = $max_width;
        } else {
            $new_width  = floor(($image_width / $image_height) * $max_height);
            $new_height = $max_height;
        }
    }
    if ($info['mime'] == 'image/jpeg') {
        $img = imagescale(imagecreatefromjpeg($source), $new_width, $new_height);
        imagejpeg($img, $source);
    } else if ($info['mime'] == 'image/webp') {
        $img = imagescale(imagecreatefromwebp($source), $new_width, $new_height);
        imagewebp($img, $source);
    } else if ($info['mime'] == 'image/png') {
        $img = imagescale(imagecreatefrompng($source), $new_width, $new_height);
        imagepng($img, $source);
    }
}
// Template header, feel free to customize this
function template_header($title)
{
    echo <<<EOT
    <!DOCTYPE html>
    <html>
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1">
    <title>$title - Pieter Adriaans</title>
    <link rel="shortcut icon" href="assets/favicon/favicon.ico" type="image/x-icon">
    <link rel="icon" type="image/png" href="assets/favicon/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="assets/favicon/favicon-16x16.png" sizes="16x16" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0/css/all.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet" type="text/css">
    <link href="jostyle.css" rel="stylesheet" type="text/css">
    <link href="assets/css/home.css" rel="stylesheet" type="text/css">
    EOT;
}
function template_header_other()
{
    echo <<<EOT
    <link href="assets/css/painting.css" rel="stylesheet" type="text/css">
    <link href="assets/css/science.css" rel="stylesheet" type="text/css">
EOT;
}

link:
function template_nav()
{
echo <<<EOT
                </head>
                <body>
                <div class="rj-foreground">
        
                <nav class="navtop">
                <input type="checkbox" id="dropdown" style="display:none">
                <label for="dropdown" class="dropdown">
                <span class="hamburger">
                <span class="icon-bar top-bar"></span>
                <span class="icon-bar middle-bar"></span>
                <span class="icon-bar bottom-bar"></span>
                </span>
                <span class="rj-nav-menu-span">Menu</span>
                </label>
                <div class="rj-nav-wrap">
                <a href="index.php" id="home"><img src="assets/svg/navHome.svg" class="rj-nav-svg"></a>
                <a href="about.php" id="home"><img src="assets/svg/navAbout.svg" class="rj-nav-svg"></a>
                <a class="rj-dropdown-art" onclick="toggleArtNav()"><img src="assets/svg/navArt.svg" class="rj-nav-svg"></a>
                <div class="rj-dropdown-nav">
                <ul class="rj-dropdown-nav-ul">
                <li><a href="gallery.php">Works 1963 - present</a></li>
                <li><a href="music.php">On Music</a></li>
                <li><a href="painting.php">On Painting</a></li>

                </ul>
                </div>

                <a href="science.php" id="science"><img src="assets/svg/navScience.svg" class="rj-nav-svg"></a>

                <a id="blog" class="rj-dropdown-biz" onclick="toggleBizNav()"><img src="assets/svg/navBusiness.svg" class="rj-nav-svg"></a>
                <div class="rj-nav-biz">
                <ul class="rj-dropdown-nav-ul">
                <li><a href="forsale.php">Art for sale</a></li>
                <li><a href="manezinho.php">Grand cafe Manezinho</a></li>
                </ul>
                </div>

                <a href="blog.php" id="blog"><img src="assets/svg/navBlog.svg" class="rj-nav-svg"></a>
                <a href="contact.php" id="contact"><img src="assets/svg/navContact.svg" class="rj-nav-svg"></a>
                </div>
                </nav>
            EOT;
}
// <a href="music.php" id="music"><i class="fa-solid fa-music"></i>Music</a>
// <a href="gallery.php" id="gallery"><i class="fas fa-photo-video"></i>Gallery</a>

// Template footer
function template_footer()
{
    echo <<<EOT
<footer class="rj-footer">
<p>2022 Pieter Adriaans
<br>
<small> Designed and Developed by Jos Velema <a href="http://www.codette.net">codette.net</a></small>
</p>
</footer> 
</div>
<script>

let artDropDown = document.querySelector('.rj-dropdown-nav');
let bizDropDown = document.querySelector('.rj-nav-biz')



function toggleArtNav() {
    if (artDropDown.style.display == "none") {
        artDropDown.style.display = "block";
        bizDropDown.style.display = "none";
    } else {
        artDropDown.style.display = "none";
    }
}

function toggleBizNav() {
    if (bizDropDown.style.display == "none") {
        bizDropDown.style.display = "block";
        artDropDown.style.display = "none"
    } else {
        bizDropDown.style.display = "none";
    }
} 
</script>
        <script src="script.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>
EOT;
}
