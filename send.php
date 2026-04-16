<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = htmlspecialchars(trim($_POST["name"] ?? ""));
    $email   = htmlspecialchars(trim($_POST["email"] ?? ""));
    $message = htmlspecialchars(trim($_POST["message"] ?? ""));

    if (empty($email) || empty($message)) {
        http_response_code(400);
        exit("Chybí povinná pole.");
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'wes1-smtp.wedos.net';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'hello@webnot.cz';
        $mail->Password   = 'Megabusiness1_';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('hello@webnot.cz', 'Web kontaktní formulář');
        $mail->addAddress('hello@webnot.cz');
        $mail->addReplyTo($email, $name);

        $mail->Subject = 'Zpráva z webu' . ($name ? " od $name" : '');
        $mail->Body    = "Jméno: $name\nEmail: $email\n\nZpráva:\n$message";

        $mail->send();
        header('Location: index.html?sent=1');
    } catch (Exception $e) {
        error_log($mail->ErrorInfo);
        header('Location: index.html?sent=0');
    }
    exit;
}
?>