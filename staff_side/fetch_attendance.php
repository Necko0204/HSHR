<?php
session_name('staff_session');
session_start();
include 'db_config.php';

if (!isset($_SESSION['employee_id'])) {
    die("Unauthorized access.");
}

$employee_id = $conn->real_escape_string($_SESSION['employee_id']);

$query = "
    SELECT 
        date,
        MIN(time_in) AS time_in, 
        MAX(time_out) AS time_out, 
        SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(time_out, time_in)))) AS total_hours,
        MIN(break_in) AS break_in,
        MAX(break_out) AS break_out,
        IFNULL(SUM(TIME_TO_SEC(break_duration)), 0) AS break_duration_seconds
    FROM attendance 
    WHERE employee_id = ? 
    GROUP BY date 
    ORDER BY date DESC
";


$stmt = $conn->prepare($query);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

$attendanceData = [];
while ($row = $result->fetch_assoc()) {
    $attendanceData[] = $row;
}

echo json_encode($attendanceData);
?>