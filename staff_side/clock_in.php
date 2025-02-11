<?php
session_name('staff_session');
session_start();
include 'db_config.php';

if (!isset($_SESSION['employee_id'])) {
    die("Unauthorized access.");
}

$employee_id = $_SESSION['employee_id'];
$date = date("Y-m-d");
$time_in = date("H:i:s");

// Check if already clocked in for today
$query = "SELECT * FROM attendance WHERE employee_id='$employee_id' AND date='$date'";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    // Insert clock-in record
    $query = "INSERT INTO attendance (employee_id, date, time_in) VALUES ('$employee_id', '$date', '$time_in')";
    if ($conn->query($query)) {
        echo "Clock-in successful at $time_in";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "You have already clocked in today.";
}
?>