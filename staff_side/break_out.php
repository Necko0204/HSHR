<?php
session_name('staff_session');
session_start();
include 'db_config.php';

if (!isset($_SESSION['employee_id']) || !in_array($_SESSION['role'], ['Staff', 'Intern'])) {
    header("Location: index.php");
    exit();
}
$employee_id = $conn->real_escape_string($_SESSION['employee_id']);
$date = date("Y-m-d");
$break_out = date("H:i:s");

// Ensure employee has clocked in and is currently on break
$query = "SELECT time_in, time_out, break_in, break_out FROM attendance WHERE employee_id = ? AND date = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $employee_id, $date);
$stmt->execute();
$stmt->bind_result($time_in, $time_out, $break_in_time, $break_out_time);
$stmt->fetch();
$stmt->close();

// Validate conditions
if (!$time_in) {
    die("⚠ Cannot end break without clocking in.");
} elseif ($time_out) {
    die("⚠ Cannot end break after clocking out.");
} elseif (!$break_in_time) {
    die("⚠ No active break found. Please break in first.");
} elseif ($break_out_time) {
    die("⚠ You have already ended your break.");
}

// Calculate break duration in seconds
$break_in_sec = strtotime($break_in_time);
$break_out_sec = strtotime($break_out);
$break_duration_seconds = $break_out_sec - $break_in_sec;

// Debug: Check if the break duration is reasonable
if ($break_duration_seconds <= 0) {
    die("⚠ Invalid break duration: $break_duration_seconds seconds.");
}

// Calculate hours, minutes, and seconds for break duration
$hours = floor($break_duration_seconds / 3600);
$minutes = floor(($break_duration_seconds % 3600) / 60);
$seconds = $break_duration_seconds % 60;

// Format the break duration with hours, minutes, and seconds
$break_duration = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);

// Update the attendance record with break_out and formatted break_duration
$query = "UPDATE attendance 
          SET break_out = ?, 
              break_duration = ? 
          WHERE employee_id = ? AND date = ? AND break_out IS NULL";

$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $break_out, $break_duration, $employee_id, $date);

if ($stmt->execute()) {
    echo "✅ Break ended successfully!";
} else {
    echo "❌ Failed to end break.";
}
?>