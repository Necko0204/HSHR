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
$automaticBreakOut = '09:32:00';
if (date('H:i:s') < $automaticBreakOut) {
    echo 'No automatic break-out is due.';
    exit;
}

$statement = $conn->prepare(
    'UPDATE attendance
     SET break_out = ?, break_duration = SEC_TO_TIME(GREATEST(TIME_TO_SEC(TIMEDIFF(?, break_in)), 0))
     WHERE employee_id = ? AND date = CURDATE() AND break_in IS NOT NULL AND break_in <= ? AND break_out IS NULL'
);
$statement->bind_param('ssss', $automaticBreakOut, $automaticBreakOut, $employeeId, $automaticBreakOut);
$statement->execute();
$updated = $statement->affected_rows;
$statement->close();
echo $updated ? 'Automatic break-out applied.' : 'No automatic break-out was needed.';
