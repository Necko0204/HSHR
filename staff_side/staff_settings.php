<?php
require_once __DIR__ . '/includes/staff_session.php';

error_reporting(E_ALL);
ini_set('display_errors', '0');

include 'staff_helper.php';
include 'db_config.php';

if (!isset($_SESSION['employee_id']) || !in_array(strtolower($_SESSION['role'] ?? ''), ['staff', 'intern'], true)) {
    header("Location: index.php");
    exit();
}

// Assign the correct session values
$sender_id = isset($_SESSION['employee_id']) ? $_SESSION['employee_id'] : "";
$sender_role = isset($_SESSION['role']) ? $_SESSION['role'] : "";


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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body style="overflow: hidden;">

<!-- Back to Dashboard Button -->
<div style="position: absolute; top: 7px; left: 20px; z-index: 1000;">
    <a href="dashboard.php" class="btn btn-secondary">
        <i class="fa fa-arrow-left"></i> Back to Dashboard
    </a>
</div>


    <div class="main-container">
        <?php include 'staff_navbar.php'; ?>
    </div>


        <!-- Animated Box Shapes -->
<!-- Animated Box Shapes -->
<div class="animation-container">
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
</div>


    <div class="profile-container">
        <img src="../images/asdasdasd123123123123123.jpg" alt="Profile Picture">
    </div>



    <main class="dashboard-container">
        <div class="card-container">
            <div class="card1" onclick="location.href='staff_viewprofile.php'">
                <i class="fa-solid fa-money-check-alt fa-4x"></i>
                <h5 class="mt-3">View Profile</h5>
            </div>
        </div>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
