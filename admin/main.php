<?php
session_start();
// Include the configuration and functions file

include_once '../config.php';
include_once '../functions.php';
// Check if admin is logged in
// inspectAndDie($_SESSION);
if (!isset($_SESSION['admin_loggedin'])) {
    header('Location: login.php');
    exit;
}

// Get the user role and name

function redirect(string $url)
{
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
    header('Location: ' . $url);
    exit;
}


$pdo = pdo_connect_mysql();
// Template admin header
function template_admin_header($title, $selected = 'dashboard')
{
    // Get the user role and name
    $user_role = $_SESSION['role'];
    $user_name = $_SESSION['name'];

    $dt = time();
    $refresh = substr($dt, -4, 4);
    $admin_links = '
        <a href="index.php"' . ($selected == 'dashboard' ? ' class="selected"' : 'title="Dashboard"') . '>
        <span class="rj-icon rj-icon-nav">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chart-histogram" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M3 3v18h18" />
            <path d="M20 18v3" />
            <path d="M16 16v5" />
            <path d="M12 13v8" />
            <path d="M8 16v5" />
            <path d="M3 11c6 0 5 -5 9 -5s3 5 9 5" />
            </svg>      
        </span>
        <span class="aside-span">Dashboard</span>
        </a>

        <a href="categories.php"' . ($selected == 'categories' ? ' class="selected"' : 'title="Categories"') . '>
        <span class="rj-icon rj-icon-nav">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-category" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M4 4h6v6h-6z" />
            <path d="M14 4h6v6h-6z" />
            <path d="M4 14h6v6h-6z" />
            <path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
            </svg>    
        </span>
        <span class="aside-span">Categories</span>
        </a>

        <a href="allmedia.php?refresh=' . $refresh . '"' . ($selected == 'MediaGallery' ? ' class="selected"' : 'title="MediaGallery"') . '>
        <span class="rj-icon rj-icon-nav">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-photo" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M15 8h.01" />
            <path d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-12z" />
            <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5" />
            <path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3" />
            </svg>
        </span>
        <span class="aside-span">Media</span>
        </a>
        <a href="sales.php?refresh=' . $refresh . '"' . ($selected == 'Sales' ? ' class="selected"' : 'title="Sales"') . '>
        <span class="rj-icon rj-icon-nav">
        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-currency-euro"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17.2 7a6 7 0 1 0 0 10" /><path d="M13 10h-8m0 4h8" /></svg>
        </span>
        <span class="aside-span">Sales</span>
        </a>
        
        <a href="multimedia.php?refresh=' . $refresh . '"' . ($selected == 'multimedia' ? ' class="selected"' : 'title="MultiMedia"') . '>
        <span class="rj-icon rj-icon-nav">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-photo-video" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M9 15h-3a3 3 0 0 1 -3 -3v-6a3 3 0 0 1 3 -3h6a3 3 0 0 1 3 3v3" />
            <path d="M9 9m0 3a3 3 0 0 1 3 -3h6a3 3 0 0 1 3 3v6a3 3 0 0 1 -3 3h-6a3 3 0 0 1 -3 -3z" />
            <path d="M3 12l2.296 -2.296a2.41 2.41 0 0 1 3.408 0l.296 .296" />
            <path d="M14 13.5v3l2.5 -1.5z" />
            <path d="M7 6v.01" />
            </svg>
        </span>

        <span class="aside-span">Multimedia/QR</span>
        </a>

        </span>
        <a href="posts.php"' . ($selected == 'posts' ? ' class="selected"' : 'title="Blog"') . '>
            <span class="rj-icon rj-icon-nav">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-message-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M8 9h8" />
                <path d="M8 13h6" />
                <path d="M9 18h-3a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-3l-3 3l-3 -3z" />
                </svg>
            </span>

            <span class="aside-span">Blog Post</span>
        </a>

        <a href="comments.php"' . ($selected == 'comments' ? ' class="selected"' : 'title="Blog Comments"') . '>
        <span class="rj-icon rj-icon-nav">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-messages" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M21 14l-3 -3h-7a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1h9a1 1 0 0 1 1 1v10" />
            <path d="M14 15v2a1 1 0 0 1 -1 1h-7l-3 3v-10a1 1 0 0 1 1 -1h2" />
            </svg>
        </span>
        <span class="aside-span">Blog Comments</span>
        </a>
        
        <a href="messages.php"' . ($selected == 'messages' ? ' class="selected"'  : 'title="Messages"') . '>
        <span class="rj-icon rj-icon-nav">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
            <path d="M3 7l9 6l9 -6" />
            </svg>
        </span>
        <span class="aside-span">Messages</span>
        </a>

        <a href="settings.php"' . ($selected == 'settings' ? ' class="selected"' : ' title="Settings"') . '>
        <span class="rj-icon rj-icon-nav">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-adjustments" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M4 10a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
            <path d="M6 4v4" />
            <path d="M6 12v8" />
            <path d="M10 16a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
            <path d="M12 4v10" />
            <path d="M12 18v2" />
            <path d="M16 7a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
            <path d="M18 4v1" />
            <path d="M18 9v11" />
            </svg>
        </span>
        <span class="aside-span">Settings</span>
        </a>
    ';
    $staff_links = '
        <a href="index.php"' . ($selected == 'dashboard' ? ' class="selected"' : 'title="Dashboard"') . '>
        <span class="rj-icon rj-icon-nav">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chart-histogram" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M3 3v18h18" />
            <path d="M20 18v3" />
            <path d="M16 16v5" />
            <path d="M12 13v8" />
            <path d="M8 16v5" />
            <path d="M3 11c6 0 5 -5 9 -5s3 5 9 5" />
            </svg>      
        </span>
        <span class="aside-span">Dashboard</span>
        </a>

        <a href="allmedia.php?refresh=' . $refresh . '"' . ($selected == 'MediaGallery' ? ' class="selected"' : 'title="MediaGallery"') . '>
        <span class="rj-icon rj-icon-nav">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-photo" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M15 8h.01" />
            <path d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-12z" />
            <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5" />
            <path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3" />
            </svg>
        </span>
        <span class="aside-span">Media</span>
        </a>
        <a href="sales.php?refresh=' . $refresh . '"' . ($selected == 'Sales' ? ' class="selected"' : 'title="Sales"') . '>
        <span class="rj-icon rj-icon-nav">
        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-currency-euro"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17.2 7a6 7 0 1 0 0 10" /><path d="M13 10h-8m0 4h8" /></svg>
        </span>
        <span class="aside-span">Sales</span>
        </a>
        

        
        <a href="messages.php"' . ($selected == 'messages' ? ' class="selected"'  : 'title="Messages"') . '>
        <span class="rj-icon rj-icon-nav">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
            <path d="M3 7l9 6l9 -6" />
            </svg>
        </span>
        <span class="aside-span">Messages</span>
        </a>


    ';
    echo <<<EOT
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1">
		<title>$title</title>
        
        
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0/css/all.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
		<link href="admin.css?v=5" rel="stylesheet" type="text/css">
		<link href="adminjostyle.css?v=5" rel="stylesheet" type="text/css">
	</head>
	<body class="admin">
        <aside class="responsive-width-100 responsive-hidden">
            
EOT;
    echo ($user_role == 'admin' ? $admin_links : $staff_links);
    echo <<<EOT
        </aside>
        <main class="responsive-width-100 full" id="$selected">
            <header>
                <div class="aside-toggle">
                    <a class="responsive-toggle" href="#">
                        <span class="rj-icon-menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-indent-increase" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M20 6l-11 0" />
                        <path d="M20 12l-7 0" />
                        <path d="M20 18l-11 0" />
                        <path d="M4 8l4 4l-4 4" />
                        </svg>
                        </span>
                    </a>
                </div>
                <div class="header-current-page" class="header-$selected">
                <p>$title</p>
                </div>
                <div class="header-links-r">
                    <span class="current-user">$user_role / $user_name</span>
                    <a href="../index.php" class="rj-icon-link" title="Go to homepage">
                        <span class="rj-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                        <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                        <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                        </svg>  
                        </span>
                    </a>            
                    <a href="about.php" title="About/Help" class="rj-icon-link">
                    <span class="rj-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-help-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                        <path d="M12 16v.01" />
                        <path d="M12 13a2 2 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" />
                        </svg>
                    </span>
                    </a>
                    <a href="logout.php" class="rj-icon-link" title="Log out">
                        <span class="rj-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-logout" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                            <path d="M9 12h12l-3 -3" />
                            <path d="M18 15l3 -3" />
                            </svg>

                        </span>
                    </a>
                </div>
            </header>
EOT;
}
// Template admin footer
function template_admin_footer()
{
    echo <<<EOT
        </main>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="./CMScripts.js"></script>
    </body> 
</html>
EOT;
}
