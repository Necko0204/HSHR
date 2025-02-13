<?php
session_name('staff_session');
session_start();
include 'db_config.php';

if (!isset($_SESSION['employee_id'])) {
    die("Unauthorized access.");
}

$employee_id = $conn->real_escape_string($_SESSION['employee_id']);
$date = date("Y-m-d");
$break_in = date("H:i:s");

// Ensure employee is clocked in but hasn't taken a break yet
$query = "SELECT time_in, time_out, break_in FROM attendance WHERE employee_id = ? AND date = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $employee_id, $date);
$stmt->execute();
$stmt->bind_result($time_in, $time_out, $existing_break_in);
$stmt->fetch();
$stmt->close();

if (!$time_in) {
    die("⚠ Cannot take a break without clocking in.");
} elseif ($time_out) {
    die("⚠ Cannot take a break after clocking out.");
} elseif ($existing_break_in) {
    die("⚠ You are already on break.");
}

// Update the existing row with break-in time
$query = "UPDATE attendance SET break_in = ? WHERE employee_id = ? AND date = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sis", $break_in, $employee_id, $date);

if ($stmt->execute()) {
    echo "✅ Break-in successful!";
} else {
    echo "❌ Error: " . $conn->error;
}

?>