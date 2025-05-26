<?php
include 'db_config.php';

date_default_timezone_set('Asia/Manila'); // Set your timezone
$date = date("Y-m-d");
$current_time = date("H:i:s");
$break_out_time = "09:32:00"; // Default break-out time

// Check if database connection is established
if (!$conn) {
    die("❌ Database connection failed: " . mysqli_connect_error());
}

// Debug: Ensure time comparison is correct


if (strtotime($current_time) >= strtotime($break_out_time)) {
    

    // Fetch employees who need an auto break-out
    $query = "SELECT employee_id, break_in FROM attendance WHERE date = ? AND break_in IS NOT NULL AND break_out IS NULL";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("⚠️ No records found for auto break-out.<br>");
    }

    while ($row = $result->fetch_assoc()) {
        $employee_id = $row['employee_id'];
        $break_in_time = strtotime($row['break_in']);
        $break_out_time_sec = strtotime($break_out_time);

      

        // Calculate break duration
        $break_duration = $break_out_time_sec - $break_in_time;
        $hours = floor($break_duration / 3600);
        $minutes = floor(($break_duration % 3600) / 60);
        $seconds = $break_duration % 60;
        $formatted_break_duration = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);

        

        // Update attendance with break-out time and break duration
        $update_query = "UPDATE attendance SET break_out = ?, break_duration = ? WHERE employee_id = ? AND date = ? AND break_in = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ssiss", $break_out_time, $formatted_break_duration, $employee_id, $date, $row['break_in']);
        
        if ($update_stmt->execute()) {
        
        } else {
            echo "❌ Update failed: " . $conn->error . "<br>";
        }
        $update_stmt->close();
    }

    $stmt->close();
    $conn->close();
    echo "✅ Auto break-out executed successfully.";
} else {
    echo "⏳ Not the designated break-out time.<br>";
}
?>