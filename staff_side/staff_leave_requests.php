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

$employee_name = $staffData['firstname'] . ' ' . $staffData['lastname']; // Store employee name

$employee_id = $_SESSION['employee_id']; // Assuming employee_id is stored in session
$query = "SELECT leave_id, leave_type_id, leave_start_date, leave_end_date, total_days, status, request_date 
          FROM leave_requests 
          WHERE employee_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
        .card-custom {
            width: 50%;
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
        <h2 class="mb-4 text-center" style="color: rgb(139, 41, 41);">Leave Requests</h2>

        <!-- Leave Request Form -->
        <div class="card2 card-custom mb-4">
            <h4 class="text-center">Submit a Leave Request</h4>
            <form action="submit_leave.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Leave Type:</label>
                    <select class="form-control" name="leave_type_id" id="leaveType" required>
                        <option value="" selected disabled>Select Type of Leave</option>
                        <option value="1">Sick Leave</option>
                        <option value="2">Vacation Leave</option>
                        <option value="3">Emergency Leave</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Select Leave Dates:</label>
                    <input type="text" id="leave_dates" class="form-control" name="leave_dates" 
                        placeholder="Select your leave dates" title="Choose your leave period" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Total Days:</label>
                    <input type="number" class="form-control" id="total_days" name="total_days" readonly 
                        placeholder="Total leave days will be calculated" title="Automatically calculated based on selected dates">
                </div>

                <button type="submit" class="btn btn-primary w-100">Submit Leave Request</button>
            </form>
        </div>

        <!-- Leave Requests Table -->
        <div class="card2 card-custom">
            <h4 class="text-center">Your Leave Requests</h4>
            <div class="table-responsive table-container">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Total Days</th>
                            <th>Status</th>
                            <th>Request Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0) { ?>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $row['leave_start_date']; ?></td>
                                    <td><?= $row['leave_end_date']; ?></td>
                                    <td><?= $row['total_days']; ?></td>
                                    <td><?= $row['status']; ?></td>
                                    <td><?= $row['request_date']; ?></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="7" class="text-center">No leave requests found.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const leaveType = document.getElementById("leaveType");
            const leaveDates = document.getElementById("leave_dates");
            const totalDaysInput = document.getElementById("total_days");

            // Remove default option once user selects a valid leave type
            leaveType.addEventListener("change", function() {
                if (leaveType.value) {
                    leaveType.querySelector("option[value='']").remove();
                }
            });

            // Flatpickr for selecting date range
            flatpickr("#leave_dates", {
                mode: "range",
                dateFormat: "Y-m-d",
                minDate: "today",
                onClose: function(selectedDates) {
                    if (selectedDates.length === 2) {
                        const startDate = selectedDates[0];
                        const endDate = selectedDates[1];

                        // Calculate total days (including start & end date)
                        const timeDiff = endDate.getTime() - startDate.getTime();
                        const daysDiff = Math.ceil(timeDiff / (1000 * 60 * 60 * 24)) + 1;

                        totalDaysInput.value = daysDiff;
                    } else {
                        totalDaysInput.value = "";
                    }
                }
            });
        });
    </script>
</body>
</html>
