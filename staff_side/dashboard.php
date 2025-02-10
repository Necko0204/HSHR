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
    <style>
        body {
            overflow: hidden;
            position: relative;
            color: white;
            font-family: 'Poppins', sans-serif;
            background: rgb(78, 46, 46);
        }

        /* Profile Section */
        .profile-container {
            text-align: center;
            margin-top: 30px;
        }

        .profile-container img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 4px solid white;
        }

        .profile-container img:hover {
            transform: scale(1.1);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.6);
        }

        /* Dashboard Container (Centered) */
        .dashboard-container {
            margin-bottom: 0;
            height: 40vh;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* Cards - Widgets */
        .card-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            max-width: 1000px;
        }

        .card {
            cursor: pointer;
            font-size: 1.3rem;
            padding: 2rem !important;
            background: rgba(255, 255, 255, 0.6); /* Increased opacity */
            border: none;
            border-radius: 12px;
            backdrop-filter: blur(10px);
            text-align: center;
            width: 220px;
            height: 200px;
            box-sizing: border-box;
            color: black; /* Ensures text remains readable */
        }

        .card:hover {
            transform: scale(1.1);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
        }

        /* Media Queries */
        @media (max-width: 768px) {
            .card-container {
                flex-direction: column;
                align-items: center;
            }

            .profile-container img {
                width: 100px;
                height: 100px;
            }

            .card {
                width: 80%;
                margin-bottom: 15px;
            }
        }
        
        @media (max-width: 576px) {
            .card {
                width: 90%;
            }
        }

    </style>
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
