<?php
declare(strict_types=1);

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../includes/admin_api.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/mailer.php';
require_once __DIR__ . '/db_config.php';

function acceptanceResponse(int $status, bool $success, string $message, array $extra = []): never
{
    http_response_code($status);
    echo json_encode(['success' => $success, 'message' => $message] + $extra);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') acceptanceResponse(405, false, 'Method not allowed.');
$applicantId = trim((string) ($_POST['applicant_id'] ?? ''));
if ($applicantId === '') acceptanceResponse(422, false, 'Applicant ID is required.');

$newEmployeeId = null;
$applicant = null;
$sequenceLockAcquired = false;
$conn->begin_transaction();
try {
    $applicantStatement = $conn->prepare(
        "SELECT lastname, firstname, middlename, gender, dateofbirth, contact, email, status
         FROM applicants WHERE applicant_id = ? LIMIT 1 FOR UPDATE"
    );
    $applicantStatement->bind_param('s', $applicantId);
    $applicantStatement->execute();
    $applicant = $applicantStatement->get_result()->fetch_assoc();
    $applicantStatement->close();
    if (!$applicant) throw new OutOfBoundsException('Applicant not found.');
    if ((string) $applicant['status'] === 'Accepted') throw new DomainException('Applicant has already been accepted.');

    $sequenceLock = $conn->query("SELECT GET_LOCK('hshr_employee_sequence', 5) AS acquired");
    $sequenceLockAcquired = (int) ($sequenceLock->fetch_assoc()['acquired'] ?? 0) === 1;
    if (!$sequenceLockAcquired) throw new RuntimeException('Employee sequence is busy.');

    $existing = $conn->prepare(
        'SELECT id FROM employees WHERE email1 = ? AND lastname = ? AND firstname = ? AND dateofbirth = ? LIMIT 1 FOR UPDATE'
    );
    $existing->bind_param('ssss', $applicant['email'], $applicant['lastname'], $applicant['firstname'], $applicant['dateofbirth']);
    $existing->execute();
    $newEmployeeId = $existing->get_result()->fetch_assoc()['id'] ?? null;
    $existing->close();

    if ($newEmployeeId === null) {
        $year = date('Y');
        $prefix = 'HS-EID' . $year;
        $lastId = $conn->prepare('SELECT id FROM employees WHERE id LIKE CONCAT(?, \'%\') ORDER BY id DESC LIMIT 1 FOR UPDATE');
        $lastId->bind_param('s', $prefix);
        $lastId->execute();
        $previous = $lastId->get_result()->fetch_assoc()['id'] ?? null;
        $lastId->close();
        $sequence = $previous ? ((int) substr((string) $previous, -7) + 1) : 1;
        $newEmployeeId = $prefix . str_pad((string) $sequence, 7, '0', STR_PAD_LEFT);

        $birthDate = new DateTimeImmutable((string) $applicant['dateofbirth']);
        $age = $birthDate->diff(new DateTimeImmutable('today'))->y;
        $insertEmployee = $conn->prepare(
            "INSERT INTO employees
             (id, lastname, firstname, middlename, gender, maritalstatus, no_of_children, `height(cm)`, `weight(kg)`,
              dateofbirth, age, mobilephone, email1, datejoiningindsclc, status, employment_type)
             VALUES (?, ?, ?, ?, ?, 'Single', 0, 0, 0, ?, ?, ?, ?, CURDATE(), 'Active', 'full_time')"
        );
        $insertEmployee->bind_param(
            'ssssssiss',
            $newEmployeeId,
            $applicant['lastname'],
            $applicant['firstname'],
            $applicant['middlename'],
            $applicant['gender'],
            $applicant['dateofbirth'],
            $age,
            $applicant['contact'],
            $applicant['email']
        );
        $insertEmployee->execute();
        $insertEmployee->close();

        $secondHalf = $conn->prepare('INSERT INTO ed_2ndhalf (employee_id) VALUES (?)');
        $secondHalf->bind_param('s', $newEmployeeId);
        $secondHalf->execute();
        $secondHalf->close();
    }

    $updateApplicant = $conn->prepare("UPDATE applicants SET status = 'Accepted' WHERE applicant_id = ?");
    $updateApplicant->bind_param('s', $applicantId);
    $updateApplicant->execute();
    $updateApplicant->close();
    $conn->commit();
    try { $conn->query("SELECT RELEASE_LOCK('hshr_employee_sequence')"); } catch (Throwable $ignored) {}
    $sequenceLockAcquired = false;
} catch (OutOfBoundsException $error) {
    $conn->rollback();
    if ($sequenceLockAcquired) try { $conn->query("SELECT RELEASE_LOCK('hshr_employee_sequence')"); } catch (Throwable $ignored) {}
    acceptanceResponse(404, false, $error->getMessage());
} catch (DomainException $error) {
    $conn->rollback();
    if ($sequenceLockAcquired) try { $conn->query("SELECT RELEASE_LOCK('hshr_employee_sequence')"); } catch (Throwable $ignored) {}
    acceptanceResponse(409, false, $error->getMessage());
} catch (Throwable $error) {
    $conn->rollback();
    if ($sequenceLockAcquired) try { $conn->query("SELECT RELEASE_LOCK('hshr_employee_sequence')"); } catch (Throwable $ignored) {}
    error_log('Applicant acceptance failed: ' . $error->getMessage());
    acceptanceResponse(500, false, 'Applicant could not be converted to an employee.');
}

$mailSent = false;
$qrCodePath = null;
try {
    $loginUrl = hshr_application_base_url() . '/staff_side/';
    $qrCodePath = tempnam(sys_get_temp_dir(), 'hshr-qr-');
    if ($qrCodePath === false) throw new RuntimeException('Temporary QR-code storage is unavailable.');
    $qrCodeImage = (new PngWriter())->write(new QrCode($loginUrl))->getString();
    if (file_put_contents($qrCodePath, $qrCodeImage, LOCK_EX) === false) throw new RuntimeException('QR code could not be created.');

    $mailer = new PHPMailer(true);
    hshr_configure_mailer($mailer);
    $fullName = trim((string) ($applicant['firstname'] . ' ' . $applicant['lastname']));
    $mailer->addAddress((string) $applicant['email'], $fullName);
    $mailer->isHTML(true);
    $mailer->Subject = 'Welcome to Holy Spirit School of Imus';
    $safeFirstName = htmlspecialchars((string) $applicant['firstname'], ENT_QUOTES, 'UTF-8');
    $safeEmployeeId = htmlspecialchars((string) $newEmployeeId, ENT_QUOTES, 'UTF-8');
    $safeLoginUrl = htmlspecialchars($loginUrl, ENT_QUOTES, 'UTF-8');
    $mailer->Body = "<p>Dear {$safeFirstName},</p><p>Your application has been accepted.</p>"
        . "<p>Your employee ID is <strong>{$safeEmployeeId}</strong>.</p>"
        . "<p>An administrator will provide your account credentials. Staff portal: <a href=\"{$safeLoginUrl}\">{$safeLoginUrl}</a></p>"
        . '<p><img src="cid:hshr-login-qr" alt="Staff portal QR code"></p>';
    $mailer->AltBody = "Your application has been accepted. Employee ID: {$newEmployeeId}. Staff portal: {$loginUrl}";
    $mailer->addEmbeddedImage($qrCodePath, 'hshr-login-qr', 'staff-portal.png');
    $mailSent = $mailer->send();
} catch (Throwable $error) {
    error_log('Acceptance email failed: ' . $error->getMessage());
} finally {
    if (is_string($qrCodePath) && is_file($qrCodePath)) @unlink($qrCodePath);
}

$message = "Applicant added as employee {$newEmployeeId}.";
if (!$mailSent) $message .= ' The employee was created, but the email could not be sent; verify SMTP configuration and notify the applicant manually.';
acceptanceResponse(200, true, $message, ['employee_id' => $newEmployeeId, 'email_sent' => $mailSent]);
