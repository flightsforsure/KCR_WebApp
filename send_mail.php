<?php
/**
 * Helper function to send an email notification via Gmail SMTP using PHPMailer.
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/lib/PHPMailer/src/PHPMailer.php';

function send_notification($toEmail, $toName, $subject, $htmlBody)
{
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = NOTIFY_FROM; // Gmail username
    $mail->Password = 'APP_PASSWORD'; // Replace with Gmail App Password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->From = NOTIFY_FROM;
    $mail->FromName = NOTIFY_FROM_NAME;
    $mail->addAddress($toEmail, $toName);

    $mail->Subject = $subject;
    $mail->Body = $htmlBody;
    $mail->AltBody = strip_tags($htmlBody);

    return $mail->send();
}
?>
