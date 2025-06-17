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
if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) || isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
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
    <meta name="author" content="Pieter Adriaans">
    <meta name="robots" content="index, follow">
    <meta name="title" content="Pieter Adriaans: Balancing Art, Science, and Business">
    <meta name="description" content="Pieter Adriaans, philosopher, painter, and entrepreneur, strives to create a balance between art, science, and business. Explore his journey from Holland to São Jorge Island, his accomplishments in AI, and his creative endeavors at Atelier de Kaasfabriek.">
    <meta name="keywords" content="Pieter Adriaans, philosopher, painter, entrepreneur, art, science, business, Artificial Intelligence, University of Amsterdam, São Jorge Island, Atelier de Kaasfabriek, Manezinho, Syllogic">
    <meta property="og:site_name" content="Pieter Adriaans">
    <meta property="og:locale" content="en_US">
    <meta property="og:title" content="Pieter Adriaans: Balancing Art, Science, and Business">
    <meta property="og:description" content="Discover the inspiring journey of Pieter Adriaans, a philosopher, painter, and entrepreneur who has dedicated his life to balancing art, science, and business. Learn about his work in AI, creative pursuits, and life on São Jorge Island.">
    <meta property="og:image" content="https://www.pieter-adriaans.com/assets/img/aboutpieterprofiel.JPG">
    <meta property="og:url" content="https://www.pieter-adriaans.com">
    <meta property="og:type" content="website">
    
    <title>$title - Pieter Adriaans</title>
    <link rel="canonical" href="https://www.pieter-adriaans.com">
    <link rel="shortcut icon" href="assets/favicon/favicon.ico" type="image/x-icon">
    <link rel="icon" type="image/png" href="assets/favicon/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="assets/favicon/favicon-16x16.png" sizes="16x16" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0/css/all.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="assets/css/style.css?v=8" rel="stylesheet" type="text/css">
    <link href="assets/css/jostyle.css?v=8" rel="stylesheet" type="text/css">
    <link href="assets/css/home.css?v=8" rel="stylesheet" type="text/css">
    <script type="application/ld+json">
    {
    "@context": "https://schema.org",
    "@type": "Person",
    "name": "Pieter Adriaans",
    "image": "http://localhost/pieter/assets/img/aboutpieterprofiel.JPG",
    "url": "http://www.pieter-adriaans.com",
    "nationality": "Dutch",
    "birthDate": "1955",
    "alumniOf": "Leiden University, University of Amsterdam",
    "worksFor": "Atelier de Kaasfabriek",
    "jobTitle": "Philosopher, Painter, Musician",
    "description": "Pieter Adriaans is a philosopher, painter, and musician who has devoted his life to harmonizing art, science, and music. His journey has taken him from Holland to São Jorge Island, where he continues to explore his creative passions.",
    "award": "PhD in Theoretical Computer Science",
    "memberOf": "University of Amsterdam",
    "hasOccupation": {
        "@type": "Occupation",
        "name": "Philosopher, Painter, Musician"
    },
    "knowsAbout": [
        "Art",
        "Science",
        "Music",
        "Artificial Intelligence",
        "Data Mining"
    ],
    "location": {
        "@type": "Place",
        "address": {
        "@type": "PostalAddress",
        "addressLocality": "São Jorge Island",
        "addressCountry": "Portugal"
        }
    }
    }
    </script>
    EOT;
}
function template_header_other()
{
    echo <<<EOT
    <link href="assets/css/painting.css?v=8" rel="stylesheet" type="text/css">
    <link href="assets/css/science.css?v=8" rel="stylesheet" type="text/css">
EOT;
}

function template_nav($page = 'home')
{
    echo  '
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
        <a href="/" class="' .  (($page === 'home') ? 'active' : '') . '" id="home">
        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none" style="margin: 0.5rem" stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-home"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>Home
        </a>
        <a href="gallery" class="' . (($page === 'gallery') ? 'active' : '') . '">Gallery</a>
        <a href="painting" class="' . (($page === 'painting') ? 'active' : '') . '">Painting</a>
        <a href="music" class="' . (($page === 'music') ? 'active' : '') . '">Music</a>
        <a href="science" class="' .  (($page === 'science') ? 'active' : '') . '">Science</a>
        <a href="blog" class="' .  (($page === 'blog') ? 'active' : '') . '">Blog</a>
        <a href="contact" class="' .  (($page === 'contact') ? 'active' : '') . '">
        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-mail"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
        </a>
        </div>
        </nav>
    ';
}

// <a href="music.php" id="music"><i class="fa-solid fa-music"></i>Music</a>
// <a href="gallery.php" id="gallery"><i class="fas fa-photo-video"></i>Gallery</a>

// Template footer
function template_footer()
{
    $currentYear = date('Y');
    echo <<<EOT
<footer class="rj-footer">
<p> $currentYear Pieter Adriaans 
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
        <script src="assets/js/script.js?v=8"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>
EOT;
}
