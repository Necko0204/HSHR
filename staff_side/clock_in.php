<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/staff_session.php';
header('Content-Type: text/plain; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Clock-in requires a POST request.';
    exit;
}
if (empty($_SESSION['employee_id'])) {
    http_response_code(401);
    echo 'Authentication required.';
    exit;
}
if (!hshr_validate_csrf()) {
    http_response_code(403);
    echo 'Refresh the attendance page and try again.';
    exit;
}

date_default_timezone_set('Asia/Manila');
require_once __DIR__ . '/db_config.php';
$employeeId = (string) $_SESSION['employee_id'];
$clientTimeRaw = trim((string) ($_POST['client_time'] ?? ''));
$clientTimestamp = strtotime($clientTimeRaw);
$serverTimestamp = time();
$ipAddress = (string) ($_SERVER['REMOTE_ADDR'] ?? '');
$userAgent = substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 1000);

if (!$clientTimestamp || abs($serverTimestamp - $clientTimestamp) > 300 || date('Y-m-d', $serverTimestamp) !== date('Y-m-d', $clientTimestamp)) {
    $attempt = $conn->prepare(
        "INSERT INTO clock_in_attempts (employee_id, attempt_time, server_time, client_time, ip_address, user_agent, status)
         VALUES (?, NOW(), FROM_UNIXTIME(?), FROM_UNIXTIME(?), ?, ?, 'Failed')"
    );
    $attempt->bind_param('siiss', $employeeId, $serverTimestamp, $clientTimestamp, $ipAddress, $userAgent);
    $attempt->execute();
    $attempt->close();
    http_response_code(422);
    echo '❌ Clock-in failed: Your system time is incorrect.';
    exit;
}

$existing = $conn->prepare('SELECT 1 FROM attendance WHERE employee_id = ? AND date = CURDATE() LIMIT 1');
$existing->bind_param('s', $employeeId);
$existing->execute();
$alreadyClockedIn = (bool) $existing->get_result()->fetch_row();
$existing->close();
if ($alreadyClockedIn) {
    http_response_code(409);
    echo 'You have already clocked in today.';
    exit;
}

$photo = (string) ($_POST['photo'] ?? '');
if (!preg_match('~^data:image/(png|jpeg);base64,(.+)$~s', $photo, $matches)) {
    http_response_code(422);
    echo 'A valid clock-in photo is required.';
    exit;
}
$imageBytes = base64_decode($matches[2], true);
if ($imageBytes === false || strlen($imageBytes) > 5 * 1024 * 1024 || @getimagesizefromstring($imageBytes) === false) {
    http_response_code(422);
    echo 'Clock-in photo must be a valid image smaller than 5 MB.';
    exit;
}

$extension = $matches[1] === 'jpeg' ? 'jpg' : 'png';
$uploadDirectory = __DIR__ . '/uploads';
if (!is_dir($uploadDirectory)) mkdir($uploadDirectory, 0755, true);
$filename = 'clockin_' . date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.' . $extension;
$diskPath = $uploadDirectory . '/' . $filename;
if (file_put_contents($diskPath, $imageBytes, LOCK_EX) === false) {
    http_response_code(500);
    echo 'Clock-in photo could not be saved.';
    exit;
}
$imagePath = 'uploads/' . $filename;

$conn->begin_transaction();
try {
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    $autoTimeout = $conn->prepare(
        "UPDATE attendance
         SET time_out = '23:59:59',
             total_hours = SEC_TO_TIME(GREATEST(TIME_TO_SEC(TIMEDIFF('23:59:59', time_in)) - COALESCE(TIME_TO_SEC(break_duration), 0), 0)),
             status = 'Auto_Timeout', manual_clockout_flag = 1
         WHERE employee_id = ? AND date = ? AND time_out IS NULL"
    );
    $autoTimeout->bind_param('ss', $employeeId, $yesterday);
    $autoTimeout->execute();
    $autoTimeout->close();

    $attendance = $conn->prepare(
        'INSERT INTO attendance (employee_id, date, time_in, ip_address, user_agent, image_path)
         VALUES (?, CURDATE(), CURTIME(), ?, ?, ?)'
    );
    $attendance->bind_param('ssss', $employeeId, $ipAddress, $userAgent, $imagePath);
    $attendance->execute();
    $attendance->close();

    $attempt = $conn->prepare(
        "INSERT INTO clock_in_attempts (employee_id, attempt_time, server_time, client_time, ip_address, user_agent, status)
         VALUES (?, NOW(), FROM_UNIXTIME(?), FROM_UNIXTIME(?), ?, ?, 'success')"
    );
    $attempt->bind_param('siiss', $employeeId, $serverTimestamp, $clientTimestamp, $ipAddress, $userAgent);
    $attempt->execute();
    $attempt->close();
    $conn->commit();
    echo '✅ Clock-in successful!';
} catch (Throwable $error) {
    $conn->rollback();
    if (is_file($diskPath)) unlink($diskPath);
    http_response_code(500);
    echo 'Clock-in could not be recorded. Please try again.';
}
