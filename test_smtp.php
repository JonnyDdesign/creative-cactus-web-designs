<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.titan.email'; // or 'smtp.hostinger.com'
    $mail->SMTPAuth = true;
    $mail->Username = 'info@creativecactuswebdesigns.com';
    $mail->Password = 'Dane1011';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('info@creativecactuswebdesigns.com', 'Creative Cactus Web Designs');
    $mail->addAddress('info@creativecactuswebdesigns.com');
    $mail->Subject = 'Test Email';
    $mail->Body    = 'This is a test email sent using PHPMailer.';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
