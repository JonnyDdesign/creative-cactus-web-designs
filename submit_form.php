<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

// Honeypot
if (!empty($_POST['website'])) {
    echo json_encode(['status' => 'error', 'message' => 'Bot detected.']);
    exit;
}

// Load .env file
$dotenv_path = __DIR__ . '/.env';
if (!file_exists($dotenv_path)) {
    echo json_encode(['status' => 'error', 'message' => 'Server configuration error: .env file missing']);
    exit;
}

$dotenv = [];
$lines = file($dotenv_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) continue;
    list($key, $value) = explode('=', $line, 2);
    $key = trim($key);
    $value = trim($value, " \t\n\r\0\x0B\"'");
    $dotenv[$key] = $value;
}

if (!isset($dotenv['RECAPTCHA_SECRET_KEY'])) {
    echo json_encode(['status' => 'error', 'message' => 'Server configuration error: reCAPTCHA key missing.']);
    exit;
}

// Verify reCAPTCHA
$recaptcha_secret = $dotenv['RECAPTCHA_SECRET_KEY'];
$recaptcha_response = $_POST['g-recaptcha-response'] ?? '';
if (empty($recaptcha_response)) {
    echo json_encode(['status' => 'error', 'message' => 'reCAPTCHA failed.']);
    exit;
}

$verify = @file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
$captcha_success = json_decode($verify);
if (!$captcha_success || !$captcha_success->success) {
    echo json_encode(['status' => 'error', 'message' => 'reCAPTCHA verification failed.']);
    exit;
}

// Get, sanitize, and trim inputs
$name = htmlspecialchars(trim($_POST['name']));
$email = htmlspecialchars(trim($_POST['email']));
$phone = htmlspecialchars(trim($_POST['phone']));
$subject = htmlspecialchars(trim($_POST['subject']));
$message = htmlspecialchars(trim($_POST['message']));

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
    exit;
}

// Basic keyword spam filtering
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
?>