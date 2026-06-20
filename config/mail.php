<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/env.php';

function sendOTP($toEmail, $otp)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();

        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];

        if ($_ENV['MAIL_ENCRYPTION'] === 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } else {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }

        $mail->Port = (int) $_ENV['MAIL_PORT'];

        $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = 'Admin OTP Verification';

        $mail->Body = "
            <h2>Your Admin OTP</h2>
            <p>Your OTP is:</p>
            <h1>{$otp}</h1>
            <p>This OTP will expire in 5 minutes.</p>
        ";

        return $mail->send();

    } catch (Exception $e) {
        return false;
    }
}
?>