<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/security.php';
hshr_start_session('public_session', '/');
header('Content-Type: application/json; charset=utf-8');

function applicationResponse(int $status, string $type, string $message): never
{
    http_response_code($status);
    echo json_encode(['status' => $type, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') applicationResponse(405, 'error', 'Method not allowed.');
if (!hshr_validate_csrf()) applicationResponse(403, 'error', 'Refresh the application page and try again.');
if ((int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 9 * 1024 * 1024) applicationResponse(413, 'error', 'Application upload is too large.');
require_once __DIR__ . '/db_config.php';

$required = [
    'lastname', 'firstname', 'email', 'gender', 'dateofbirth', 'contact',
    'highest_degree', 'university', 'graduation_year', 'previous_school',
    'years_of_experience', 'license'
];
foreach ($required as $field) {
    if (trim((string) ($_POST[$field] ?? '')) === '') applicationResponse(422, 'error', 'Please complete all required fields.');
}

$email = filter_var(trim((string) $_POST['email']), FILTER_VALIDATE_EMAIL);
$graduationYear = filter_var($_POST['graduation_year'], FILTER_VALIDATE_INT);
$experience = filter_var($_POST['years_of_experience'], FILTER_VALIDATE_INT);
$birthDate = DateTimeImmutable::createFromFormat('!Y-m-d', (string) $_POST['dateofbirth']);
if (!$email || !$graduationYear || $experience === false || !$birthDate || $birthDate > new DateTimeImmutable('today')) {
    applicationResponse(422, 'error', 'Please provide valid contact, education, and birth-date information.');
}
if ($graduationYear < 1950 || $graduationYear > ((int) date('Y') + 10) || $experience < 0 || $experience > 80) {
    applicationResponse(422, 'error', 'Enter valid education and experience values.');
}
if (!in_array((string) $_POST['gender'], ['Male', 'Female', 'Other'], true)) {
    applicationResponse(422, 'error', 'Select a valid gender.');
}
if (!preg_match('/^\+?[0-9 ()-]{7,20}$/', trim((string) $_POST['contact']))) {
    applicationResponse(422, 'error', 'Enter a valid contact number.');
}
if (!hshr_rate_limit_consume('employment-application', (string) $email, 5, 3600)) {
    header('Retry-After: 3600');
    applicationResponse(429, 'error', 'Too many applications were submitted. Please try again later.');
}

$resume = $_FILES['resume'] ?? null;
if (!is_array($resume) || ($resume['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    applicationResponse(422, 'error', 'A resume is required.');
}
if ((int) $resume['size'] > 8 * 1024 * 1024) applicationResponse(422, 'error', 'Resume must be smaller than 8 MB.');
if (!is_uploaded_file((string) $resume['tmp_name'])) applicationResponse(422, 'error', 'Resume upload is invalid.');

$mime = (new finfo(FILEINFO_MIME_TYPE))->file($resume['tmp_name']);
$allowed = [
    'application/pdf' => 'pdf',
    'application/msword' => 'doc',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
];
if (!isset($allowed[$mime])) applicationResponse(422, 'error', 'Resume must be a PDF, DOC, or DOCX file.');

$uploadDirectory = __DIR__ . '/applicants_uploads';
if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0755, true) && !is_dir($uploadDirectory)) {
    applicationResponse(500, 'error', 'Resume storage is unavailable.');
}
$resumeName = 'resume_' . bin2hex(random_bytes(14)) . '.' . $allowed[$mime];
$resumeDiskPath = $uploadDirectory . '/' . $resumeName;
if (!move_uploaded_file($resume['tmp_name'], $resumeDiskPath)) applicationResponse(500, 'error', 'Resume could not be saved.');
$resumePath = 'applicants_uploads/' . $resumeName;

$values = [];
foreach (['lastname', 'firstname', 'middlename', 'gender', 'contact', 'highest_degree', 'university', 'major', 'previous_school', 'subjects_taught', 'license', 'certifications'] as $field) {
    $values[$field] = trim((string) ($_POST[$field] ?? ''));
    if (strlen($values[$field]) > (in_array($field, ['subjects_taught', 'certifications'], true) ? 4000 : 190)) {
        if (is_file($resumeDiskPath)) unlink($resumeDiskPath);
        applicationResponse(422, 'error', 'One or more application fields are too long.');
    }
}
$birthDateValue = $birthDate->format('Y-m-d');
$year = date('Y');

$conn->begin_transaction();
try {
    $lockResult = $conn->query("SELECT GET_LOCK('hshr_applicant_sequence', 5) AS acquired");
    if ((int) ($lockResult->fetch_assoc()['acquired'] ?? 0) !== 1) throw new RuntimeException('Application sequence is busy.');
    $prefix = "APP-HSHI{$year}";
    $lastStatement = $conn->prepare('SELECT applicant_id FROM applicants WHERE applicant_id LIKE CONCAT(?, \'%\') ORDER BY applicant_id DESC LIMIT 1 FOR UPDATE');
    $lastStatement->bind_param('s', $prefix);
    $lastStatement->execute();
    $last = $lastStatement->get_result()->fetch_assoc()['applicant_id'] ?? null;
    $lastStatement->close();
    $next = $last ? ((int) substr((string) $last, -5) + 1) : 1;
    $applicantId = $prefix . str_pad((string) $next, 5, '0', STR_PAD_LEFT);

    $statement = $conn->prepare(
        'INSERT INTO applicants
         (applicant_id, lastname, firstname, middlename, email, gender, dateofbirth, contact,
          highest_degree, university, graduation_year, major, previous_school, years_of_experience,
          subjects_taught, license, certifications, resume_path, submitted_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())'
    );
    $statement->bind_param(
        'ssssssssssississss',
        $applicantId, $values['lastname'], $values['firstname'], $values['middlename'], $email,
        $values['gender'], $birthDateValue, $values['contact'], $values['highest_degree'],
        $values['university'], $graduationYear, $values['major'], $values['previous_school'],
        $experience, $values['subjects_taught'], $values['license'], $values['certifications'], $resumePath
    );
    $statement->execute();
    $statement->close();
    $conn->commit();
    try { $conn->query("SELECT RELEASE_LOCK('hshr_applicant_sequence')"); } catch (Throwable $ignored) {}
    applicationResponse(201, 'success', 'Application submitted successfully.');
} catch (Throwable $error) {
    $conn->rollback();
    try { $conn->query("SELECT RELEASE_LOCK('hshr_applicant_sequence')"); } catch (Throwable $ignored) {}
    if (is_file($resumeDiskPath)) unlink($resumeDiskPath);
    error_log('Employment application failed: ' . $error->getMessage());
    applicationResponse(500, 'error', 'Application could not be submitted. Please try again.');
}
