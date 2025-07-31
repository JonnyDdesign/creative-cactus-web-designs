<?php

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Honeypot trap field (invisible to humans)
    if (!empty($_POST['website'])) {
        echo json_encode(['status' => 'error', 'message' => 'Bot detected.']);
        exit;
    }

    // Load secret from .env file
    $dotenv_path = __DIR__ . '/.env';
    if (!file_exists($dotenv_path)) {
        echo json_encode(['status' => 'error', 'message' => 'Server configuration error: .env file missing']);
        exit;
    }

    $dotenv = parse_ini_file($dotenv_path);

    header('Content-Type: application/json');
    var_dump($dotenv);

    if (!$dotenv || !isset($dotenv['RECAPTCHA_SECRET_KEY'])) {
        echo json_encode(['status' => 'error', 'message' => 'Server configuration error: reCAPTCHA key missing.']);
        exit;
    }

    $recaptcha_secret = $dotenv['RECAPTCHA_SECRET_KEY'];
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

    if (empty($recaptcha_response)) {
        echo json_encode(['status' => 'error', 'message' => 'reCAPTCHA failed.']);
        exit;
    }

    $verify = @file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
    
    if (!$verify) {
        echo json_encode(['status' => 'error', 'message' => 'Server error: could not reach reCAPTCHA service.'];
        exit;)
    }
    $captcha_success = json_decode($verify);

    if (!$captcha_success || !$captcha_success->success) {
        error_log("reCAPTCHA failed: " . $verify);
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
*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Starting test...\n";

// Check if .env exists
$dotenv_path = __DIR__ . '/../.env';
if (!file_exists($dotenv_path)) {
    echo "ERROR: .env file is missing\n";
    exit;
}

$dotenv = [];
$lines = file($dotenv_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    if (strpos(trim($line), '#') === 0 || !strpos($line, '=')) continue;
    list($key, $value) = explode('=', $line, 2);
    $dotenv[trim($key)] = trim($value);
}

echo '<pre>';
print_r($dotenv);
echo '</pre>';
exit;

if (!isset($dotenv['RECAPTCHA_SECRET_KEY'])) {
    echo "ERROR: RECAPTCHA_SECRET_KEY missing in .env file\n";
    exit;
}

echo "reCAPTCHA key loaded successfully: " . substr($dotenv['RECAPTCHA_SECRET_KEY'], 0, 5) . "...\n";

?>