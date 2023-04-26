<?php
session_start();
// Include the configuration and functions file
include_once '../config.php';
include_once '../functions.php';
// Check if admin is logged in
if (!isset($_SESSION['admin_loggedin'])) {
    header('Location: login.php');
    exit;
}
$pdo = pdo_connect_mysql();
// Template admin header
function template_admin_header($title, $selected = 'dashboard') {
    $admin_links = '
        <a href="index.php"' . ($selected == 'dashboard' ? ' class="selected"' : '') . '><i class="fas fa-tachometer-alt"></i>Dashboard</a>
        <a href="categories.php"' . ($selected == 'categories' ? ' class="selected"' : '') . '><i class="fas fa-list"></i>Categories</a>
        <a href="allmedia.php"' . ($selected == 'allmedia' ? ' class="selected"' : '') . '><i class="fas fa-images"></i>Media</a>
        <a href="multimedia.php"' . ($selected == 'multimedia' ? ' class="selected"' : '') . '><i class="fas fa-images"></i>Multimedia/QR</a>

        <a href="posts.php"' . ($selected == 'posts' ? ' class="selected"' : '') . '><i class="fa-regular fa-comment-dots"></i>Blog posts</a>
        <a href="comments.php"' . ($selected == 'comments' ? ' class="selected"' : '') . '><i class="fa-regular fa-comment-dots"></i>Blog comments</a>
        
        <a href="messages.php"' . ($selected == 'messages' ? ' class="selected"' : '') . '<i class="fa-solid fa-envelope"></i>Messages</a>

        <a href="settings.php"' . ($selected == 'settings' ? ' class="selected"' : '') . '><i class="fas fa-tools"></i>Settings</a>
    ';
echo <<<EOT
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1">
		<title>$title</title>
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
		<link href="admin.css?v=5" rel="stylesheet" type="text/css">
		<link href="adminjostyle.css?v=5" rel="stylesheet" type="text/css">

		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0/css/all.css">
		


	</head>
	<body class="admin">
        <aside class="responsive-width-100 responsive-hidden">
            <h1>Admin Panel</h1>
            $admin_links
        </aside>
        <main class="responsive-width-100">
            <header>
                <a class="responsive-toggle" href="#">
                    <i class="fas fa-bars"></i>
                </a>
                <a href="../index.php">Go to homepage</a>
                <div class="space-between"></div>
                <a href="about.php" class="right"><i class="fas fa-question-circle"></i></a>
                <a href="logout.php" class="right"><i class="fas fa-sign-out-alt"></i></a>
            </header>
EOT;
}
// Template admin footer
function template_admin_footer() {
echo <<<EOT
        </main>
        <script>
        document.querySelector(".responsive-toggle").onclick = function(event) {
            event.preventDefault();
            let aside = document.querySelector("aside"), main = document.querySelector("main"), header = document.querySelector("header");
            let asideStyle = window.getComputedStyle(aside);
            if (asideStyle.display == "none") {
                aside.classList.remove("closed", "responsive-hidden");
                main.classList.remove("full");
                header.classList.remove("full");
            } else {
                aside.classList.add("closed", "responsive-hidden");
                main.classList.add("full");
                header.classList.add("full");
            }
        };
        </script>
    </body>
</html>
EOT;
}
?>
