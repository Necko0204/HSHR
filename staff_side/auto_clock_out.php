<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/staff_session.php';
header('Content-Type: text/plain; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method not allowed.';
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
if (date('H:i:s') < '23:59:00') {
    echo 'No automatic clock-out is due.';
    exit;
}

$statement = $conn->prepare(
    "UPDATE attendance
     SET time_out = '23:59:59',
         total_hours = SEC_TO_TIME(GREATEST(TIME_TO_SEC(TIMEDIFF('23:59:59', time_in)) - COALESCE(TIME_TO_SEC(break_duration), 0), 0)),
         status = 'Auto_Timeout', manual_clockout_flag = 1
     WHERE employee_id = ? AND date = CURDATE() AND time_out IS NULL"
);
$statement->bind_param('s', $employeeId);
$statement->execute();
$updated = $statement->affected_rows;
$statement->close();
echo $updated ? 'Automatic clock-out applied.' : 'No automatic clock-out was needed.';
