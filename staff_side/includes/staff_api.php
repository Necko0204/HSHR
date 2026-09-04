<?php
declare(strict_types=1);

require_once __DIR__ . '/staff_session.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, max-age=0');

function staff_api_respond(int $status, bool $success, string $message, array $data = []): never
{
    http_response_code($status);
    echo json_encode(['success' => $success, 'status' => $success ? 'success' : 'error', 'message' => $message] + $data);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    staff_api_respond(405, false, 'This endpoint requires a POST request.');
}
if (empty($_SESSION['employee_id'])) {
    staff_api_respond(401, false, 'Authentication required.');
}
if (!in_array(strtolower((string) ($_SESSION['role'] ?? '')), ['staff', 'intern'], true)) {
    staff_api_respond(403, false, 'Your account is not permitted to perform this action.');
}
if (!hshr_validate_csrf()) {
    staff_api_respond(403, false, 'Refresh the page and try again.');
}

$staffApiEmployeeId = (string) $_SESSION['employee_id'];

