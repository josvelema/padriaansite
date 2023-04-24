<?php
// Set the time period for which the page should be cached (in seconds)
$cache_time = 60; // 3600 1 hour

// Get the last modified time of the page
$last_modified = filemtime(__FILE__);

// Set the cache control headers
header("Cache-Control: public, max-age=$cache_time");
header("Expires: " . gmdate('D, d M Y H:i:s', time() + $cache_time) . ' GMT');
header("Last-Modified: " . gmdate('D, d M Y H:i:s', $last_modified) . ' GMT');

// Check if the browser has a cached version of the page
if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) || isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
    header('HTTP/1.1 304 Not Modified');
    exit;
}
// Template header, feel free to customize this
function template_header($title)
{
    echo <<<EOT
    <!DOCTYPE html>
    <html>
    <head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7EZLG850Z4"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-7EZLG850Z4');
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1">
    <title>$title - Pieter Adriaans</title>
    <link rel="shortcut icon" href="assets/favicon/favicon.ico" type="image/x-icon">
    <link rel="icon" type="image/png" href="assets/favicon/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="assets/favicon/favicon-16x16.png" sizes="16x16" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0/css/all.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="style.css?v=4" rel="stylesheet" type="text/css">
    <link href="jostyle.css?v=4" rel="stylesheet" type="text/css">
    <link href="assets/css/home.css?v=4" rel="stylesheet" type="text/css">
    EOT;
}
function template_header_other()
{
    echo <<<EOT
    <link href="assets/css/painting.css?v=4" rel="stylesheet" type="text/css">
    <link href="assets/css/science.css?v=4" rel="stylesheet" type="text/css">
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
                <li><a href="painting.php">Painting</a></li>
                <li><a href="music.php">Music</a></li>
                <li><a href="gallery.php">Virtual Museum</a></li>
                

                </ul>
                </div>

                <a href="science.php" id="science"><img src="assets/svg/navScience.svg" class="rj-nav-svg"></a>

                <a id="blog" class="rj-dropdown-biz" onclick="toggleBizNav()"><img src="assets/svg/navBusiness.svg" class="rj-nav-svg"></a>
                <div class="rj-nav-biz">
                <ul class="rj-dropdown-nav-ul">
                <li><a href="manezinho.php">Grand cafe Manezinho</a></li>
                <li><a href="kaasfabriek.php">Atelier de Kaasfabriek</a></li>
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
	    
        <script src="node_modules/lozad/dist/lozad.min.js"></script>
        <script src="script.js?v=5"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>
EOT;
}
