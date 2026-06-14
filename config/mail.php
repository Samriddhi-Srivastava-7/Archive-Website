<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function sendOTP($toEmail, $otp)
{
    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();

        $mail->Host = 'smtp.gmail.com';

        $mail->SMTPAuth = true;

        $mail->Username = 'ichchhit235@gmail.com';

        $mail->Password =  'svif dcum pfpl dpur';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port = 587;

        $mail->setFrom('ichchhit235@gmail.com','Certificate Archive');

        $mail->addAddress($toEmail);

        $mail->isHTML(true);

        $mail->Subject = 'Admin OTP Verification';

        $mail->Body = "
            <h2>Your Admin OTP</h2>
            <p>Your OTP is:</p>
            <h1>$otp</h1>
            <p>This OTP will expire in 5 minutes.</p>
        ";

        $mail->send();

        return true;

    } catch (Exception $e) {

        return false;

    }
}
?>