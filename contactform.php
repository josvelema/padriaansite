<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'functions.php';
// Connect to MySQL
$pdo = pdo_connect_mysql();

session_start();
file_put_contents('debug.log', 'Script started' . PHP_EOL, FILE_APPEND);


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/autoload.php';
require 'config.php';

$mail = new PHPMailer(true);


file_put_contents('debug.log', '$_POST contents: ' . print_r($_POST, true) . PHP_EOL, FILE_APPEND);

if (isset($_POST['name'], $_POST['email'], $_POST['msg'], $_POST['subject'], $_POST['g-recaptcha-response'])) {
    $errors = [];
    file_put_contents('debug.log', 'Inside if statement' . PHP_EOL, FILE_APPEND);
    // $extra = [
    //     'name' => $_POST['name']
    // ];

    // if (isset($_POST['achtername'])) {
    //     $extra['achtername'] = $_POST['achtername'];
    // }

    // if (isset($_POST['telefoonnummer'])) {
    //     $extra['telefoonnummer'] = $_POST['telefoonnummer'];
    // }

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address!';
    }

    if (!preg_match('/^[a-zA-Z ]+$/', $_POST['name'])) {
        $errors['name'] = 'name can only contain letters!';
    }

    if (empty($_POST['msg'])) {
        $errors['msg'] = 'Voer  a.u.b. een msg in!';
    }

    $recaptcha = new \ReCaptcha\ReCaptcha(RECAPTCHA_SECRET_KEY);
    $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

    if (!$resp->isSuccess()) {
        $errors['recaptcha'] = 'Captcha validation failed. Please try again.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO messages (email, subject, msg) VALUES (?,?,?)');
        $stmt->execute([$_POST['email'], $_POST['subject'], $_POST['msg']]);

        try {
            if (SMTP) {
                $mail->isSMTP();
                $mail->Host = smtp_host;
                $mail->SMTPAuth = true;
                $mail->Username = smtp_username;
                $mail->Password = smtp_password;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = smtp_port;
            }

            $mail->setFrom(mail_from, $_POST['name']);
            $mail->addAddress(support_email, 'Support');
            $mail->addReplyTo($_POST['email'], $_POST['name']);

            $mail->isHTML(true);
            $mail->Subject = $_POST['subject'] . ' : ' . $_POST['name'];
            $mail->Body = $_POST['msg'];
            $mail->AltBody = strip_tags($_POST['msg']);

            $mail->send();
            $response = [
                'success' => 'Message sent successfully.'
            ];
            echo json_encode($response);
        } catch (Exception $e) {
            $errors[] = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
            $response = [
                'errors' => array_values($errors)
            ];
            echo json_encode($response);
        }
    } else {
        $response = [
            'errors' => $errors
        ];
        echo json_encode($response);
    }
}
