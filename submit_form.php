<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    $to = 'info@creativecactuswebdesigns.com';
    $subjectLine = "Contact Form Submission $subject";
    $headers = "From: $name <$email>" . "\r\n" .
               "Reply-To: $email" . "\r\n" .
               "X-Mailer: PHP/" . phpversion();

    $mailBody = "Name: $name\nEmail: $email\nPhone: $phone\nSubject: $subject\n\nMessage:\n$message";

    if (mail($to, $subject, $mailBody, $headers)) {
        echo 'Thank you! Your message has been sent successfully.';
    } else {
        echo "Sorry, something went wrong. Please try again later.";
    }
}
?>
