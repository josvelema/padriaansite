<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();
include_once '../config.php';
include_once '../functions.php';
$msg = '';
$msg_staff = '';

if (isset($_POST['admin_username'], $_POST['admin_password'])) {
    if ($_POST['admin_username'] == admin_user && $_POST['admin_password'] == admin_pass) {
        $_SESSION['admin_loggedin'] = true;
        $_SESSION['role'] = 'admin';
        $_SESSION['name'] = admin_user;
        header('Location: index.php');
        exit;
    } else {
        $msg = 'Incorrect username and/or password!';
    }
}

if (isset($_POST['staff_username'], $_POST['staff_password'])) {
    $pdo = pdo_connect_mysql();
    $stmt = $pdo->prepare('SELECT * FROM accounts WHERE username = ?');
    $stmt->execute([$_POST['staff_username']]);
    $staff = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($staff && password_verify($_POST['staff_password'], $staff['user_pw'])) {
        $_SESSION['admin_loggedin'] = true;
        $_SESSION['role'] = $staff['user_role'];
        $_SESSION['name'] = $staff['username'];
        // $msg_staff = 'You are now logged in!';
        header('Location: index.php');
        exit;
    } else {
        $msg_staff = 'Incorrect username and/or password!';
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin/Staff Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1">
    <link href="admin.css" rel="stylesheet" type="text/css">
</head>

<body class="login">
    <form action="" method="post" class="">
        <h2>Administrator login</h2>
        <input type="text" name="admin_username" placeholder="Username" required>
        <input type="password" name="admin_password" placeholder="Password" required>
        <input type="submit" value="Login">
        <p><?= $msg ?></p>
    </form>
    <form action="" method="post" class="">
        <h2>Staff Login</h2>
        <input type="text" name="staff_username" placeholder="Username" required>
        <input type="password" name="staff_password" placeholder="Password" required>
        <input type="submit" value="Login">
        <p><?= $msg_staff ?></p>
    </form>
</body>

</html>