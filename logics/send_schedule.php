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
$scheduleRaw = trim((string) ($_POST['schedule'] ?? ''));
$schedule = DateTimeImmutable::createFromFormat('!Y-m-d\TH:i', $scheduleRaw)
    ?: DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', $scheduleRaw);
if ($applicantId === '' || !$schedule || $schedule < new DateTimeImmutable('-5 minutes')) {
    http_response_code(422);
    echo json_encode(['status' => 'error', 'message' => 'Select an applicant and a valid future interview schedule.']);
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

$displaySchedule = $schedule->format('F j, Y \a\t g:i A');
$mailer = new PHPMailer(true);
try {
    hshr_configure_mailer($mailer);
    $mailer->addAddress((string) $applicant['email'], trim((string) ($applicant['firstname'] . ' ' . $applicant['lastname'])));
    $mailer->isHTML(true);
    $mailer->Subject = 'Interview schedule - Holy Spirit Human Resource';
    $mailer->Body = '<p>Dear ' . htmlspecialchars((string) $applicant['firstname'], ENT_QUOTES, 'UTF-8') . ',</p>'
        . '<p>Your interview has been scheduled for <strong>' . htmlspecialchars($displaySchedule, ENT_QUOTES, 'UTF-8') . '</strong>.</p>'
        . '<p>Best regards,<br>Holy Spirit Human Resource Team</p>';
    $mailer->AltBody = "Your interview has been scheduled for {$displaySchedule}.";
    $mailer->send();

    $scheduledAt = $schedule->format('Y-m-d H:i:s');
    $update = $conn->prepare("UPDATE applicants SET status = 'for_interview', interview_date = ? WHERE applicant_id = ?");
    $update->bind_param('ss', $scheduledAt, $applicantId);
    $update->execute();
    $update->close();

    echo json_encode(['status' => 'success', 'message' => 'Interview schedule sent successfully.']);
} catch (Throwable $error) {
    error_log('Interview email failed: ' . $error->getMessage());
    http_response_code($error instanceof RuntimeException ? 503 : 502);
    echo json_encode(['status' => 'error', 'message' => 'The interview email could not be sent. Check the email configuration and try again.']);
}
