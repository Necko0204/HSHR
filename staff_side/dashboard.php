<?php
session_name('staff_session');
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_config.php';

if (!isset($_SESSION['employee_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../images/asdasdasd123123123123123.jpg">
    <title>Staff Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.css" rel="stylesheet">
    <link rel="stylesheet" href="background.css">
</head>
<body>

    <!-- Navigation Bar (Unchanged) -->
    <div class="main-container">
        <?php include 'staff_navbar.php'; ?>
    </div>

    <!-- Profile Picture -->
    <div class="profile-container">
        <img src="../images/asdasdasd123123123123123.jpg" alt="Profile Picture">
    </div>

    <!-- Dashboard (Widgets Centered) -->
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

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
