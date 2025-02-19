<?php
session_name('staff_session');
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'staff_helper.php';
include 'db_config.php';

if (!isset($_SESSION['employee_id']) || $_SESSION['role'] !== 'staff') {
    header("Location:index.php");
    exit();
}

// Assign the correct session values
$sender_id = isset($_SESSION['employee_id']) ? $_SESSION['employee_id'] : "";
$sender_role = isset($_SESSION['role']) ? $_SESSION['role'] : "";

// Debugging: Check if values are now correctly assigned
error_log("Sender ID: " . $sender_id);
error_log("Sender Role: " . $sender_role);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../images/asdasdasd123123123123123.jpg">
    <title>Staff Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="background.css">

    <style>
        .image-placeholder {
            width: 100px; /* Set to your image's width */
            height: 100px; /* Set to your image's height */
            background-color: #f0f0f0; /* Placeholder color */
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="floating-container"></div> <!-- Floating squares container -->

    <div class="main-container">
        <?php include 'staff_navbar.php'; ?>
    </div>

    <div class="profile-container">
        <img src="../images/asdasdasd123123123123123.jpg" alt="Profile Picture">
    </div>

    <main class="dashboard-container">
        <div class="card-container">
            <div class="card" onclick="location.href='staff_payroll.php'">
                <i class="fa-solid fa-money-check-alt fa-4x"></i>
                <h5 class="mt-3">Payroll</h5>
            </div>
            <div class="card" onclick="location.href='staff_attendance.php'">
                <i class="fa-solid fa-calendar-check fa-4x"></i>
                <h5 class="mt-3">Attendance</h5>
            </div>
            <div class="card" onclick="location.href='staff_leave_requests.php'">
                <i class="fa-solid fa-user-clock fa-4x"></i>
                <h5 class="mt-3">Leave Requests</h5>
            </div>
            <div class="card" onclick="location.href='staff_settings.php'">
                <i class="fa-solid fa-cogs fa-4x"></i>
                <h5 class="mt-3">Settings</h5>
            </div>
        </div>
    </main>

    <script src="background.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
