<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure this path is correct

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));
    $recaptcha_secret = '6LckjS0qAAAAANAJTjRXMxnMSNQMKPJXX2SqA3Kd'; // Replace with your reCAPTCHA secret key
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // Verify CAPTCHA
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
    $responseKeys = json_decode($response, true);

    if (intval($responseKeys["success"]) !== 1) {
        die("Please complete the CAPTCHA");
    }

    // Set up PHPMailer
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'info@creativecactuswebdesigns.com';
        $mail->Password = 'Dane1011!';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom($email, $name);
        $mail->addAddress('info@creativecactuswebdesigns.com');

        // Content
        $mail->isHTML(true);
        $mail->Subject = "Contact Form Submission: $subject";
        $mail->Body    = "Name: $name<br>Email: $email<br>Phone: $phone<br>Subject: $subject<br><br>Message:<br>$message";

        $mail->send();
        echo "Thank you for your message. It has been sent.";
    } catch (Exception $e) {
        echo "Sorry, something went wrong. Please try again later. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Invalid request.";
}
?>
