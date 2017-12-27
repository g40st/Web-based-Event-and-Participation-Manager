<?php
/**
 * This file contains all the functions to send emails.
 *
 * author: Christian Högerle
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../helper/vendor/Exception.php';
require '../helper/vendor/PHPMailer.php';
require '../helper/vendor/SMTP.php';

require '../conf/SMTP_config.php';

// new user has registered
function sendMailToAdmin($from) {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = SMTP_DEBUG;
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        $mail->setFrom(SMTP_USERNAME);

        $mail->addAddress(ADMIN_EMAIL);     // send this mail to the admin

        //Content
        $mail->isHTML(false);
        $mail->Subject = 'Neuer Benutzer (DGH)';
        $mail->Body    = 'Ein neuer Benutzer hat sich angemeldet. Bitte freischalten! ' . $from;

        $mail->send();
    } catch (Exception $e) {
        echo 'Message could not be sent.';
        //echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
}





















?>
