<?php
session_name('staff_session');
session_start();
include 'db_config.php';

if (!isset($_SESSION['employee_id'])) {
    die("Unauthorized access.");
}

$employee_id = $_SESSION['employee_id'];
$leave_type_id = $_POST['leave_type_id'];
$leave_dates = $_POST['leave_dates']; // e.g., "2025-02-11 to 2025-02-20"

// Split the leave dates
list($leave_start_date, $leave_end_date) = explode(" to ", $leave_dates);

// Calculate the total leave days
$start_date = new DateTime($leave_start_date);
$end_date = new DateTime($leave_end_date);
$total_days = $start_date->diff($end_date)->days + 1; // Include the last day

// Prepare the SQL statement
$query = "INSERT INTO leave_requests (employee_id, leave_type_id, leave_start_date, leave_end_date, total_days, status, request_date) 
          VALUES (?, ?, ?, ?, ?, 'Pending', NOW())";

$stmt = $conn->prepare($query);
$stmt->bind_param("sissi", $employee_id, $leave_type_id, $leave_start_date, $leave_end_date, $total_days);

if ($stmt->execute()) {
    $_SESSION['success_message'] = "Leave request submitted successfully!";
    header("Location: staff_leave_requests.php");
    exit();
} else {
    $_SESSION['error_message'] = "Error: " . $stmt->error;
    header("Location: staff_leave_requests.php");
    exit();
}
?>
