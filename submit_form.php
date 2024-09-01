<?php
// Include the PHPMailer library
require 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Email configuration
$sender_email = getenv('TITAN_EMAIL');
$sender_password = getenv('TITAN_PASSWORD');
$recipient_email = 'info@creativecactuswebdesigns.com';
$smtp_server = 'smtp.titan.email';
$smtp_port = 587;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    $email_subject = "New Contact Form Submission: $subject";
    $email_body = "Name: $name\n";
    $email_body = "Email: $email\n";
    $email_body = "Phone: $phone\n";
    $email_body = "Subject: $subject\n";
    $email_body = "Message: $message\n";

    // Send the email using PHPMailer
    $mail = new \PHPMailer\PHPMailer\PHPMailer();

    try {
        // Configure SMTP settings
        $mail->isSMTP();
        $mail->Host = $smtp_server;
        $mail->Port = $smtp_port;
        $mail->SMTPAuth = true;
        $mail->Username = $sender_email;
        $mail->Password = $sender_password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        // Set the email content
        $mail->setFrom($sender_email, $name);
        $mail->addAddress($recipient_email);
        $mail->Subject = $email_subject;
        $mail->Body = $email_body;

        // Send the email
        if ($mail->send()) {
            echo 'Thank you! Your message has been sent successfully.';
        } else {
            echo 'There was an error sending your message: ' . $mail->ErrorInfo;
        }
    } catch (Exception $e) {
        echo 'Error sending email: ' . $e->getMessage();
    }
} else {
    echo 'Invalid request method.';
}
?>
