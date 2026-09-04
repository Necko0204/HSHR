<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../includes/admin_api.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/mailer.php';
require_once __DIR__ . '/db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
    exit;
}
$applicantId = trim((string) ($_POST['applicant_id'] ?? ''));
if ($applicantId === '') {
    http_response_code(422);
    echo json_encode(['status' => 'error', 'message' => 'Applicant ID is required.']);
    exit;
}

$statement = $conn->prepare("SELECT firstname, lastname, email FROM applicants WHERE applicant_id = ? AND status <> 'Accepted' LIMIT 1");
$statement->bind_param('s', $applicantId);
$statement->execute();
$applicant = $statement->get_result()->fetch_assoc();
$statement->close();
if (!$applicant || !filter_var($applicant['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Applicant record not found.']);
    exit;
}

$mailer = new PHPMailer(true);
try {
    hshr_configure_mailer($mailer);
    $mailer->addAddress((string) $applicant['email'], trim((string) ($applicant['firstname'] . ' ' . $applicant['lastname'])));
    $mailer->isHTML(true);
    $mailer->Subject = 'Application update - Holy Spirit Human Resource';
    $mailer->Body = '<p>Dear ' . htmlspecialchars((string) $applicant['firstname'], ENT_QUOTES, 'UTF-8') . ',</p>'
        . '<p>Thank you for your interest. After careful consideration, we will not be moving forward with your application.</p>'
        . '<p>Best regards,<br>Holy Spirit Human Resource Team</p>';
    $mailer->AltBody = 'Thank you for your interest. After careful consideration, we will not be moving forward with your application.';
    $mailer->send();

    $update = $conn->prepare("UPDATE applicants SET status = 'Rejected' WHERE applicant_id = ? AND status <> 'Accepted'");
    $update->bind_param('s', $applicantId);
    $update->execute();
    $update->close();

    echo json_encode(['status' => 'success', 'message' => 'Applicant has been rejected and notified.']);
} catch (Throwable $error) {
    error_log('Applicant rejection email failed: ' . $error->getMessage());
    http_response_code($error instanceof RuntimeException ? 503 : 502);
    echo json_encode(['status' => 'error', 'message' => 'The notification email could not be sent. Check the email configuration and try again.']);
}
