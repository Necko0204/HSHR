<?php
require_once __DIR__ . '/includes/staff_session.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, max-age=0');

ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

include 'db_config.php';

if (!isset($_SESSION['employee_id']) || !in_array(strtolower((string) ($_SESSION['role'] ?? '')), ['staff', 'intern'], true)) {
    http_response_code(401);
    echo json_encode(['error' => 'Authentication required.']);
    exit;
}

$employee_id = $_SESSION['employee_id'];

// Fetch leave requests for the logged-in employee
$query = "
    SELECT lr.*, lt.leave_name
    FROM leave_requests lr
    LEFT JOIN leave_types lt ON lr.leave_type_id = lt.leave_type_id
    WHERE lr.employee_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

$leaveRequests = [];
while ($row = $result->fetch_assoc()) {
    $leaveRequests[] = $row;
}

// Fetch leave types for the dropdown
$leaveTypeQuery = "SELECT leave_type_id, leave_name, max_days FROM leave_types ORDER BY leave_type_id";
$leaveTypeResult = $conn->query($leaveTypeQuery);

$leaveTypes = [];
while ($row = $leaveTypeResult->fetch_assoc()) {
    $leaveTypes[] = $row;
}

// Return both leave requests and leave types as a JSON response
echo json_encode([
    'leave_requests' => $leaveRequests,
    'leave_types' => $leaveTypes
]);
?>
