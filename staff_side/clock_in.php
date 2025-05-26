<?php
session_name('staff_session');
session_start();
include 'db_config.php';

date_default_timezone_set('Asia/Manila');

if (!isset($_SESSION['employee_id'])) {
    die("Unauthorized access.");
}

$employee_id = $conn->real_escape_string($_SESSION['employee_id']);
$ip_address = $_SERVER['REMOTE_ADDR'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];

$result = $conn->query("SELECT NOW() AS server_time");
$row = $result->fetch_assoc();
$server_time = strtotime($row['server_time']);

if (!$server_time) {
    die("❌ Clock-in failed: Could not retrieve server time.");
}

$server_datetime = date("Y-m-d H:i:s", $server_time);

if (!isset($_POST['client_time']) || empty($_POST['client_time'])) {
    die("❌ Clock-in failed: No client time received.");
}

$client_time = strtotime($_POST['client_time']);

if (!$client_time) {
    die("❌ Clock-in failed: Invalid client time format.");
}

$client_datetime = date("Y-m-d H:i:s", $client_time);

if (date("Y-m-d", $server_time) !== date("Y-m-d", $client_time) || abs($server_time - $client_time) > 300) {
    $log_query = "INSERT INTO clock_in_attempts (employee_id, attempt_time, server_time, client_time, ip_address, user_agent, status) 
                  VALUES ('$employee_id', NOW(), '$server_datetime', '$client_datetime', '$ip_address', '$user_agent', 'Failed')";
    $conn->query($log_query);

    die("❌ Clock-in failed: Your system date/time does not match the server date/time!\n Server Date/Time: $client_datetime\nDevice Date/Time: $server_datetime");

}

// **Check if the user has already clocked in today**
$query = "SELECT image_path FROM attendance WHERE employee_id='$employee_id' AND date=CURDATE()";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    if (!empty($row['image_path'])) {
        echo "📸 Already clocked in.";
        exit;
    }
}

// **Handle Image Upload (Only if user has not clocked in)**
if (isset($_POST['photo'])) {
    $imageData = $_POST['photo'];
    $imageData = str_replace('data:image/png;base64,', '', $imageData);
    $imageData = base64_decode($imageData);
    
    $imageFileName = "uploads/clockin_" . date("Ymd_His") . ".png";
    file_put_contents($imageFileName, $imageData);
} else {
    die("❌ Clock-in failed: No image provided.");
}

// **Auto-timeout Process for Yesterday**
$yesterday = date("Y-m-d", strtotime("-1 day"));
$auto_timeout_query = "UPDATE attendance SET time_out='23:59:59', total_hours=TIMESTAMPDIFF(SECOND, time_in, '23:59:59')/3600 WHERE employee_id='$employee_id' AND date='$yesterday' AND time_out IS NULL";
$conn->query($auto_timeout_query);

// **Reminder for Auto-Timeout**
$query = "SELECT * FROM attendance WHERE employee_id='$employee_id' AND date='$yesterday' AND status='Auto_Timeout'";
$result = $conn->query($query);

$reminder_script = "";
if ($result->num_rows > 0) {
    $reminder_script = "
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Reminder!',
                text: 'You forgot to time out yesterday. Please be mindful today!',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Okay'
            });
        </script>";
}

// **Insert Attendance Record**
$query = "INSERT INTO attendance (employee_id, date, time_in, ip_address, user_agent, image_path) 
          VALUES ('$employee_id', CURDATE(), NOW(), '$ip_address', '$user_agent', '$imageFileName')";

if ($conn->query($query)) {
    $log_query = "INSERT INTO clock_in_attempts (employee_id, attempt_time, server_time, client_time, ip_address, user_agent, status) 
                  VALUES ('$employee_id', NOW(), '$server_datetime', '$client_datetime', '$ip_address', '$user_agent', 'success')";
    $conn->query($log_query);

    echo "✅ Clock-in successful!";
    echo $reminder_script;
    exit;
} else {
    die("Error: " . $conn->error);
}
?>