<?php
declare(strict_types=1);

require_once __DIR__ . '/admin_session.php';
if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    exit('Authentication required.');
}

require_once __DIR__ . '/db_config.php';
$type = (string) ($_GET['type'] ?? '');
$recordId = trim((string) ($_GET['id'] ?? ''));
if ($recordId === '') {
    http_response_code(422);
    exit('A record ID is required.');
}

$relativePath = null;
$baseDirectory = null;
if ($type === 'resume') {
    $statement = $conn->prepare('SELECT resume_path FROM applicants WHERE applicant_id = ? LIMIT 1');
    $statement->bind_param('s', $recordId);
    $baseDirectory = dirname(__DIR__) . '/applicants_uploads';
} elseif ($type === 'attendance') {
    $attendanceId = filter_var($recordId, FILTER_VALIDATE_INT);
    if (!$attendanceId) {
        http_response_code(422);
        exit('Invalid attendance record.');
    }
    $statement = $conn->prepare('SELECT image_path FROM attendance WHERE id = ? LIMIT 1');
    $statement->bind_param('i', $attendanceId);
    $baseDirectory = dirname(__DIR__) . '/staff_side/uploads';
} else {
    http_response_code(404);
    exit('File type not found.');
}

$statement->execute();
$row = $statement->get_result()->fetch_row();
$statement->close();
if ($row) $relativePath = (string) $row[0];

$baseRealPath = realpath($baseDirectory);
$candidate = $relativePath !== null ? basename(str_replace('\\', '/', $relativePath)) : '';
$fileRealPath = $baseRealPath !== false && $candidate !== '' ? realpath($baseRealPath . DIRECTORY_SEPARATOR . $candidate) : false;
if ($baseRealPath === false || $fileRealPath === false || !str_starts_with($fileRealPath, $baseRealPath . DIRECTORY_SEPARATOR) || !is_file($fileRealPath)) {
    http_response_code(404);
    exit('File not found.');
}

$mime = (new finfo(FILEINFO_MIME_TYPE))->file($fileRealPath) ?: 'application/octet-stream';
$inlineTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$disposition = in_array($mime, $inlineTypes, true) ? 'inline' : 'attachment';
header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($fileRealPath));
header('Content-Disposition: ' . $disposition . '; filename="' . rawurlencode($candidate) . '"');
header('Cache-Control: private, no-store, max-age=0');
readfile($fileRealPath);
exit;

