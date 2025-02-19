<?php
session_name('staff_session');
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_config.php';
include 'staff_helper.php';

if (!isset($_SESSION['employee_id']) || $_SESSION['role'] !== 'staff') {
    header("Location:index.php");
    exit();
}

// Assign the correct session values
$sender_id = isset($_SESSION['employee_id']) ? $_SESSION['employee_id'] : "";
$sender_role = isset($_SESSION['role']) ? $_SESSION['role'] : "";

// Fetch staff data
$staffData = getStaffData($_SESSION['employee_id']);

if (!$staffData) {
    die("❌ Staff data not found.");
}

// Fetch attendance records for the logged-in employee
$query = "
    SELECT 
        date,
        MIN(time_in) AS time_in, 
        MAX(time_out) AS time_out, 
        SEC_TO_TIME(SUM(IF(time_out IS NOT NULL, TIME_TO_SEC(TIMEDIFF(time_out, time_in)), 0))) AS total_hours,
        MIN(break_in) AS break_in,
        MAX(break_out) AS break_out,
        IFNULL(SUM(break_duration), 0) AS break_duration
    FROM attendance 
    WHERE employee_id = ? 
    GROUP BY date 
    ORDER BY date DESC
";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}

$stmt->bind_param("i", $_SESSION['employee_id']);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Attendance</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.css" rel="stylesheet">
    <link rel="stylesheet" href="background.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: rgb(78, 46, 46) !important;
        }
        .floating-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            overflow: hidden;
            background: linear-gradient(120deg, rgba(0, 0, 0, 0.8), rgba(167, 1, 1, 0.7)); /* Professional subtle background */
        }

        .floating-shape {
            position: absolute;
            opacity: 0.6;
            animation: float infinite ease-in-out;
            filter: blur(2px);
        }

        /* Shapes */
        .circle {
            width: 80px;
            height: 80px;
            background-color: rgba(255, 255, 255, 0.4);
            border-radius: 50%;
        }

        .square {
            width: 100px;
            height: 100px;
            background-color: rgba(255, 255, 255, 0.4);
            border-radius: 10px;
        }

        .triangle {
            width: 0;
            height: 0;
            border-left: 18px solid transparent;
            border-right: 18px solid transparent;
            border-bottom: 30px solid rgba(255, 255, 255, 0.4);
        }

        /* Elegant Motion */
        @keyframes float {
            0% { 
                transform: translateY(0) translateX(0) rotate(0deg); 
                opacity: 0.6; 
            }
            50% { 
                transform: translateY(-60px) translateX(-40px) rotate(20deg); 
                opacity: 0.4; 
            }
            100% { 
                transform: translateY(0) translateX(0) rotate(40deg); 
                opacity: 0.6; 
            }
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
        /* Adjustments for table design */
        .table th, .table td {
            text-align: center;
        }
        .table th {
            background-color: #343a40;
            color: white;
        }
        .btn-lg {
            font-size: 16px;
            padding: 10px 25px;
        }
    </style>
</head>
<body>
    
    <div class="floating-container">
          <!-- Floating Shapes -->
          <div class="floating-shape circle" style="top: 10%; left: 10%;"></div>
        <div class="floating-shape square" style="top: 20%; left: 25%;"></div>
        <div class="floating-shape triangle" style="top: 30%; left: 40%;"></div>
        <div class="floating-shape circle" style="top: 60%; left: 70%;"></div>
        <div class="floating-shape square" style="top: 80%; left: 85%;"></div>
        <div class="floating-shape triangle" style="top: 50%; left: 50%;"></div>
        <div class="floating-shape circle" style="top: 40%; left: 60%;"></div>
    </div> <!-- Floating squares container -->

    <!-- Back to Dashboard Button -->
    <div style="position: absolute; top: 20px; left: 20px; z-index: 1000;">
        <a href="dashboard.php" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Navigation Bar (unchanged) -->
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

        <!-- Buttons for Clock In, Clock Out, Break In, Break Out -->
        <div class="mb-4 d-flex gap-3">
            <button class="btn btn-success btn-lg" onclick="clockIn()">
                <i class="fa fa-sign-in-alt"></i> Clock In
            </button>
            <button class="btn btn-danger btn-lg" onclick="clockOut()">
                <i class="fa fa-sign-out-alt"></i> Clock Out
            </button>
            <button class="btn btn-success btn-lg" onclick="breakIn()">
                <i class="fa fa-coffee"></i> Break In
            </button>
            <button class="btn btn-danger btn-lg" onclick="breakOut()">
                <i class="fa fa-clock"></i> Break Out
            </button>
        </div>

     <!-- Attendance Table Card -->
<div class="card-custom2">
    <div class="card-body">
        <h3 class="text-center mb-3" style="color: black;">Attendance Log</h3>
        <div class="table-responsive table-container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Total Hours</th>
                        <th>Break In</th>
                        <th>Break Out</th>
                        <th>Break Duration</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0) { ?>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['date']); ?></td>
                                <td><?php echo !empty($row['time_in']) ? htmlspecialchars($row['time_in']) : 'N/A'; ?></td>
                                <td><?php echo !empty($row['time_out']) ? htmlspecialchars($row['time_out']) : 'N/A'; ?></td>
                                <td><?php echo !empty($row['total_hours']) ? htmlspecialchars($row['total_hours']) : 'N/A'; ?></td>
                                <td><?php echo !empty($row['break_in']) ? htmlspecialchars($row['break_in']) : 'N/A'; ?></td>
                                <td><?php echo !empty($row['break_out']) ? htmlspecialchars($row['break_out']) : 'N/A'; ?></td>
                                <td>
                                    <?php
                                    if (!empty($row['break_duration']) && is_numeric($row['break_duration'])) {
                                        // Convert seconds to HH:MM:SS format
                                        $break_duration_seconds = (int) $row['break_duration'];
                                        $hours = floor($break_duration_seconds / 3600);
                                        $minutes = floor(($break_duration_seconds % 3600) / 60);
                                        $seconds = $break_duration_seconds % 60;
                                        echo sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="7" class="text-center">No attendance records found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


    <script>
document.addEventListener('DOMContentLoaded', function() {
    updateAttendanceTable(); // Populate the table when the page is loaded
});

    function showNotification(responseText) {
        Swal.fire({
            title: "Notification",
            text: responseText,
            icon: responseText.includes("✅") ? "success" : "warning"
        }).then(() => {
            if (responseText.includes("✅")) {
                updateAttendanceTable();
            }
        });
    }

    function updateAttendanceTable() {
        fetch('fetch_attendance.php')
    .then(response => response.json())
    .then(data => {
        console.log(data);  // Check what the response looks like
        const tbody = document.querySelector('table tbody');
        tbody.innerHTML = ''; // Clear existing rows

        data.forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = ` 
                <td>${row.date}</td>
                <td>${row.time_in || 'N/A'}</td>
                <td>${row.time_out || 'N/A'}</td>
                <td>${row.total_hours || 'N/A'}</td>
                <td>${row.break_in || 'N/A'}</td>
                <td>${row.break_out || 'N/A'}</td>
                <td>${formatBreakDuration(row.break_duration_seconds)}</td>
            `;
            tbody.appendChild(tr);
        });
    })
    .catch(error => console.error('Error fetching attendance:', error));

    }

    function formatBreakDuration(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    }

    function clockIn() { 
        fetch('clock_in.php')
            .then(response => response.text())
            .then(data => showNotification(data));
    }

    function clockOut() { 
        fetch('clock_out.php')
            .then(response => response.text())
            .then(data => showNotification(data));
    }

    function breakIn() { 
        fetch('break_in.php')
            .then(response => response.text())
            .then(data => showNotification(data));
    }

    function breakOut() { 
        fetch('break_out.php')
            .then(response => response.text())
            .then(data => showNotification(data));
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="background.js"></script>

</body>
</html>
