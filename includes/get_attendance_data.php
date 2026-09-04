<?php
require_once __DIR__ . '/admin_session.php';
if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    exit;
}
require 'db_config.php'; // Include your database connection

// Get total active teachers count
$totalQuery = "SELECT COUNT(employee_id) AS total_active_teachers FROM staff_accounts WHERE status = 'active'";
$totalResult = $conn->query($totalQuery);
$totalData = $totalResult->fetch_assoc();
$totalActiveTeachers = $totalData['total_active_teachers'] ?? 0;

// Get attendance data
$attendanceQuery = "SELECT
            SUM(CASE WHEN time_in IS NOT NULL THEN 1 ELSE 0 END) AS present_count,
            SUM(CASE WHEN time_in IS NULL THEN 1 ELSE 0 END) AS absent_count
          FROM attendance";
$attendanceResult = $conn->query($attendanceQuery);
$attendanceData = $attendanceResult->fetch_assoc();

// Return JSON response
echo json_encode([
    'totalActiveTeachers' => $totalActiveTeachers, // Total active teachers (100%)
    'present' => $attendanceData['present_count'] ?? 0,
    'absent' => $attendanceData['absent_count'] ?? 0
]);
?>
