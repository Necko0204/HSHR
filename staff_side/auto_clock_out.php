<?php
include 'db_config.php';

date_default_timezone_set('Asia/Manila'); // Set your timezone
$date = date("Y-m-d");
$current_time = date("H:i:s");
$default_timeout = "23:59:59"; // Auto-timeout time

if (strtotime($current_time) >= strtotime($default_timeout)) {
    // Find users who clocked in but didn't clock out
    $query = "SELECT employee_id, time_in, 
                     IFNULL(SUM(TIME_TO_SEC(TIMEDIFF(break_out, break_in))), 0) AS total_break 
              FROM attendance 
              WHERE date = ? AND time_out IS NULL 
              GROUP BY employee_id, time_in";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $employee_id = $row['employee_id'];
        $time_in = strtotime($row['time_in']);
        $time_out_sec = strtotime($default_timeout);
        $break_duration = $row['total_break']; // Break duration in seconds

        // Calculate total worked time (excluding break duration)
        $worked_seconds = ($time_out_sec - $time_in) - $break_duration;
        $worked_seconds = max($worked_seconds, 0); // Prevent negative values

        // Convert to HH:MM:SS format
        $worked_hours = gmdate("H:i:s", $worked_seconds);

        // Update attendance with auto-timeout (without updating break_out)
        $update_query = "UPDATE attendance 
        SET time_out=?, total_hours=?, status='Auto_Timeout', 
            manual_clockout_flag = 1
        WHERE employee_id=? AND date=?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ssis", $default_timeout, $worked_hours, $employee_id, $date);
        $update_stmt->execute();
        $update_stmt->close();
    }

    $stmt->close();
    $conn->close();
    echo "✅ Auto clock-out executed successfully. Total hours updated.";
} else {
    echo "⏳ Not the designated auto clock-out time.";
}
?>
