<?php
session_name('staff_session');
session_start();
include 'db_config.php';

if (!isset($_SESSION['employee_id'])) {
    die("Unauthorized access.");
}

$employee_id = $_SESSION['employee_id'];
$date = date("Y-m-d");
$time_out = date("H:i:s");

// Check if the user has clocked in before clocking out
$query = "SELECT * FROM attendance WHERE employee_id='$employee_id' AND date='$date'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    if ($row['time_out'] == NULL) {
        // Calculate total hours worked
        $time_in = strtotime($row['time_in']);
        $time_out_sec = strtotime($time_out);
        $worked_hours = round(($time_out_sec - $time_in) / 3600, 2);

        // Update the attendance record with time_out and total_hours
        $query = "UPDATE attendance SET time_out='$time_out', total_hours='$worked_hours' WHERE employee_id='$employee_id' AND date='$date'";
        if ($conn->query($query)) {
            echo "Clock-out successful at $time_out. Total worked hours: $worked_hours";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "You have already clocked out today.";
    }
} else {
    echo "No clock-in record found. Please clock in first.";
}
?>