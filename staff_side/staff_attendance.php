<?php
session_name('staff_session');
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_config.php';
include 'staff_helper.php';

// Ensure the session is active
if (!isset($_SESSION['employee_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch staff data
$staffData = getStaffData($_SESSION['employee_id']);

if (!$staffData) {
    die("❌ Staff data not found.");
}

// Fetch attendance records for the logged-in employee
$query = "SELECT date, time_in, time_out, total_hours FROM attendance WHERE employee_id = ? ORDER BY date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['employee_id']);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../images/asdasdasd123123123123123.jpg">
    <title>My Attendance</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.css" rel="stylesheet">
    <link rel="stylesheet" href="background.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .content-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 30px;
        }
        .profile-container {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 15px;
            border: 3px solid #007bff;
        }
        .profile-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .card-custom2 {
            width: 60%;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            background-color: white;
        }
        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>
<body>

    <!-- Back to Dashboard Button -->
    <div style="position: absolute; top: 20px; left: 20px; z-index: 1000;">
        <a href="dashboard.php" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Navigation Bar -->
    <div class="main-container">
        <?php include 'staff_navbar.php'; ?>
    </div>

    <div class="container content-wrapper">
        
        <!-- Profile Picture Container -->
        <div class="profile-container">
            <img src="../images/asdasdasd123123123123123.jpg" alt="Profile Picture">
        </div>

        <h2 class="mb-4 text-center" style="color: rgb(139, 41, 41);">
            Welcome, <?php echo htmlspecialchars($staffData['username']); ?>
        </h2>

        <!-- Buttons for Clock In and Clock Out -->
        <div class="mb-4 d-flex gap-3">
            <button class="btn btn-success btn-lg" onclick="clockIn()">
                <i class="fa fa-sign-in-alt"></i> Clock In
            </button>
            <button class="btn btn-danger btn-lg" onclick="clockOut()">
                <i class="fa fa-sign-out-alt"></i> Clock Out
            </button>
        </div>

        <!-- Attendance Table Card -->
        <div class="card2 card-custom2">
            <div class="card-body2">
                <h3 class="text-center mb-3" style="color: black;">Attendance Log</h3>
                <div class="table-responsive table-container">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Total Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0) { ?>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $row['date']; ?></td>
                                        <td><?php echo $row['time_in']; ?></td>
                                        <td><?php echo $row['time_out'] ?? 'N/A'; ?></td>
                                        <td><?php echo $row['total_hours'] ?? 'N/A'; ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="4" class="text-center">No attendance records found.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function clockIn() {
            fetch('clock_in.php')
                .then(response => response.text())
                .then(data => alert(data))
                .catch(error => console.error(error));
        }

        function clockOut() {
            fetch('clock_out.php')
                .then(response => response.text())
                .then(data => alert(data))
                .catch(error => console.error(error));
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>
</html>
