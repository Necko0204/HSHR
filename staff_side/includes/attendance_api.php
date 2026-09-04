<?php
declare(strict_types=1);

require_once __DIR__ . '/staff_session.php';

date_default_timezone_set('Asia/Manila');
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, max-age=0');

function attendance_respond(bool $success, string $message, int $status = 200, array $data = []): never
{
    http_response_code($status);
    echo json_encode(
        ['success' => $success, 'message' => $message] + $data,
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    );
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    attendance_respond(false, 'This attendance action requires a POST request.', 405);
}

if (empty($_SESSION['employee_id'])) {
    attendance_respond(false, 'Your staff session has expired. Please sign in again.', 401);
}

if (!hshr_validate_csrf()) {
    attendance_respond(false, 'Refresh the page and try again.', 403);
}

$attendanceEmployeeId = (string) $_SESSION['employee_id'];
