<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
        exit;
    }

    $to = 'info@creativecactuswebdesigns.com';
    $subjectLine = "Contact Form Submission $subject";
    $headers = "From: $name <$email>" . "\r\n" .
               "Reply-To: $email" . "\r\n" .
               "X-Mailer: PHP/" . phpversion();

    $mailBody = "Name: $name\nEmail: $email\nPhone: $phone\nSubject: $subject\n\nMessage:\n$message";

    if (mail($to, $subjectLine, $mailBody, $headers)) {
        echo json_encode(['status' => 'success', 'message' => 'Thank you! Your message has been sent successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Sorry, something went wrong. Please try again later.']);
    }
}
?>
