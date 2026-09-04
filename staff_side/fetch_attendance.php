<?php
require_once __DIR__ . '/includes/staff_session.php';
include 'db_config.php';

if (!isset($_SESSION['employee_id'])) {
    die("Unauthorized access.");
}

$employee_id = $conn->real_escape_string($_SESSION['employee_id']);

$query = "
  SELECT
        a.date,
        a.time_in,
        a.time_out,
        a.break_in,
        a.break_out,
        a.break_duration,
        a.total_hours,
        IFNULL(ou.status, 'on time') AS status,
        IFNULL(ou.hours, 0) AS hours
    FROM attendance a
    LEFT JOIN overtime_undertime_logs ou
        ON a.employee_id = ou.employee_id AND a.date = ou.date
    WHERE a.employee_id = ?
    ORDER BY a.date DESC
";


$stmt = $conn->prepare($query);
$stmt->bind_param("s", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

$attendanceData = [];
while ($row = $result->fetch_assoc()) {
    $attendanceData[] = $row;
}

echo json_encode($attendanceData);
?>
