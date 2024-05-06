<?php 
session_start();

include_once '../config.php';
include_once '../functions.php';
// Check if admin is logged in
if (!isset($_SESSION['admin_loggedin'])) {
    header('Location: login.php');
    exit;
}
$pdo = pdo_connect_mysql();


// Now we check if the data was submitted, isset() function will check if the data exists.
if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
	// Could not get the data that should have been sent.
	exit('Please complete the registration form!');
}

// Make sure the submitted registration values are not empty.
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
	// One or more values are empty.
	exit('Please complete the registration form');
}

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	exit('Email is not valid!');
}

if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
  exit('Username is not valid!');
}


// We need to check if the account with that username exists.

// Prepare our SQL, preparing the SQL statement will prevent SQL injection.

$stmt = $pdo->prepare('SELECT * FROM accounts WHERE username = ? OR email = ?');

// Execute the statement and fetch the user from the database
$stmt->execute([$_POST['username'], $_POST['email']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// If the user exists, then we know the username is in use
if ($user) {
    // Username already exists
    echo 'Username or email already exists, please choose another!';
    exit;
}

// Username does not exist, insert new account
$stmt = $pdo->prepare('INSERT INTO accounts (username, user_pw, email, created_at) VALUES (?, ?, ?, NOW())');

// Hash the password
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$stmt->execute([$_POST['username'], $password, $_POST['email']]);
// Output message
echo 'You have successfully registered, you can now login!';
?>


