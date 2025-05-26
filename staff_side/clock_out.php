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
$query = "SELECT time_in, time_out, break_in, break_out FROM attendance WHERE employee_id=? AND date=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $employee_id, $date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stmt->close(); // ✅ Close statement after fetching data

    if ($row['time_out'] !== NULL) {
        die("❌ You have already clocked out today.");
    }

    if ($row['time_out'] === '23:59:59') {
        die("❌ Auto-timeout was applied");
    }

    if ($row['break_in'] !== NULL && $row['break_out'] === NULL) {
        die("❌ You must break out before clocking out.");
    }

    // Get the current day (e.g., Monday, Tuesday)
    $day_of_week = date('l');

    // Get required hours and work_schedule_id from work_schedules table
    $required_hours_query = "SELECT required_hours, id AS work_schedule_id FROM work_schedules WHERE employee_id=? AND day_of_week=?";
    $req_stmt = $conn->prepare($required_hours_query);
    $req_stmt->bind_param("ss", $employee_id, $day_of_week);
    $req_stmt->execute();
    $req_result = $req_stmt->get_result();
    $req_row = $req_result->fetch_assoc();
    $req_stmt->close(); // ✅ Close after fetching

    $required_hours = $req_row['required_hours'] ?? 8; // Default to 8 hours if not set
    $work_schedule_id = $req_row['work_schedule_id'] ?? NULL;

    // Calculate worked hours using SQL instead of PHP
    $worked_hours_query = "SELECT TIMESTAMPDIFF(SECOND, time_in, ?) / 3600 AS worked_hours FROM attendance WHERE employee_id=? AND date=?";
    $worked_stmt = $conn->prepare($worked_hours_query);
    $worked_stmt->bind_param("sis", $time_out, $employee_id, $date);
    $worked_stmt->execute();
    $worked_result = $worked_stmt->get_result();
    $worked_hours = $worked_result->fetch_assoc()['worked_hours'] ?? 0;
    $worked_stmt->close(); // ✅ Close after getting result

    // Determine overtime/undertime
    if ($worked_hours > $required_hours) {
        $status = 'overtime';
        $hours = $worked_hours - $required_hours;
    } elseif ($worked_hours < $required_hours) {
        $status = 'undertime';
        $hours = $required_hours - $worked_hours;
    } else {
        $status = 'on time';
        $hours = 0;
    }

    // Update attendance record
        $update_query = "UPDATE attendance 
        SET time_out = ?, 
            total_hours = SEC_TO_TIME(
                GREATEST(
                    TIMESTAMPDIFF(SECOND, 
                        STR_TO_DATE(CONCAT(date, ' ', time_in), '%Y-%m-%d %H:%i:%s'), 
                        STR_TO_DATE(CONCAT(date, ' ', ?), '%Y-%m-%d %H:%i:%s')
                    ), 
                0)
            ), 
            status = 'Present' 
            WHERE employee_id = ? AND date = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ssis", $time_out, $time_out, $employee_id, $date);

    if ($update_stmt->execute()) {
        $update_stmt->close(); // ✅ Close after execution

        // Insert or update overtime_undertime_logs
        $log_query = "INSERT INTO overtime_undertime_logs (employee_id, work_schedule_id, date, status, hours) 
                      VALUES (?, ?, ?, ?, ?)
                      ON DUPLICATE KEY UPDATE status=?, hours=?";
        $log_stmt = $conn->prepare($log_query);
        $log_stmt->bind_param("iissdss", $employee_id, $work_schedule_id, $date, $status, $hours, $status, $hours);
        $log_stmt->execute();
        $log_stmt->close();

        echo "✅ Clock-out successful! Total Hours Updated.";
    } else {
        die("❌ Error: " . $conn->error);
    }
} else {
    die("❌ No clock-in record found. Please clock in first.");
}

$conn->close();
?>