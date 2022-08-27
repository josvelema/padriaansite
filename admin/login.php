<?php
session_start();
include_once '../config.php';
$msg = '';
if (isset($_POST['admin_username'], $_POST['admin_password'])) {
    if ($_POST['admin_username'] == admin_user && $_POST['admin_password'] == admin_pass) {
        $_SESSION['admin_loggedin'] = true;
        header('Location: index.php');
        exit;
    } else {
        $msg = 'Incorrect username and/or password!';
    }
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Admin Login</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,minimum-scale=1">
        <link href="admin.css" rel="stylesheet" type="text/css">
	</head>
	<body class="login">
        <form action="" method="post" class="">
            <input type="text" name="admin_username" placeholder="Username" required>
            <input type="password" name="admin_password" placeholder="Password" required>
            <input type="submit" value="Login">
            <p><?=$msg?></p>
        </form>
    </body>
</html>
