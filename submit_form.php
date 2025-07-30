<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Honeypot trap field (invisible to humans)
    if (!empty($_POST['website'])) {
        // A bot filled in the honeypot field
        echo json_encode(['status' => 'error', 'message' => 'Bot detected.']);
        exit;
    }

    // Google reCAPTCHA verification
    $recaptcha_secret = '6Lf6vZMrAAAAAO45pEn8fOmF-cu4j155qYqH20az';
    $recaptcha_response = $_POST['g-recaptcha-response'];

    if (empty($recaptcha_response)) {
        echo json_encode(['status' => 'error', 'message' => 'reCAPTCHA failed.']);
        exit;
    }

    $verify = @file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
    $captcha_success = json_decode($verify);

    if (!$verify || !$captcha_success || !$captcha_success->success) {
        echo json_encode(['status' => 'error', 'message' => 'reCAPTCHA verification failed.']);
        exit;
    }

    // Sanitize and trim input
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Prevent header injection
    if (preg_match("/[\r\n]/", $name) || preg_match("/[\r\n]/", $email)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input detected.']);
        exit;
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
        exit;
    }

    // Basic keyword spam filtering (adjust list as needed)
    $spam_keywords = ['seo', 'rank', 'backlinks', 'traffic', 'google ranking', 'marketing', 'guest post', 'DA', 'DR'];
    foreach ($spam_keywords as $word) {
        if (stripos($message, $word) !== false || stripos($subject, $word) !== false) {
            echo json_encode(['status' => 'error', 'message' => 'Spam detected.']);
            exit;
        }
    }

    // Compose and send email
    $to = 'info@creativecactuswebdesigns.com';
    $subjectLine = "Contact Form Submission $subject";
    $headers = "From: $name <$email>" . "\r\n" .
               "Reply-To: $email" . "\r\n" .
               "X-Mailer: PHP/" . phpversion();

    $mailBody = "Name: $name\nEmail: $email\nPhone: $phone\nSubject: $subject\n\nMessage:\n$message";

    // Send email
    if (mail($to, $subjectLine, $mailBody, $headers)) {
        echo json_encode(['status' => 'success', 'message' => 'Thank you! Your message has been sent successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Sorry, something went wrong. Please try again later.']);
    }
}
?>
