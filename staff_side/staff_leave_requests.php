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
    die("\u274C Staff data not found.");
}

$employee_name = $staffData['firstname'] . ' ' . $staffData['lastname'];
$employee_id = $_SESSION['employee_id'];

// Fetch leave requests for the logged-in employee
$query = "SELECT lr.leave_id, lt.leave_name, lr.leave_start_date, lr.leave_end_date, lr.total_days, lr.status, lr.request_date 
          FROM leave_requests lr
          JOIN leave_types lt ON lr.leave_type_id = lt.leave_type_id
          WHERE lr.employee_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch leave types for the dropdown
$leaveTypeQuery = "SELECT leave_type_id, leave_name, max_days FROM leave_types ORDER BY leave_type_id";
$leaveTypeResult = $conn->query($leaveTypeQuery);
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
    
    <div class="row justify-content-center">
        <div class="col-md-4" style="flex: 0.8; max-width: 35%;">
            <div class="card2 card-custom mb-4" style="padding: 40px; min-height: 500px; width: 100%;">
                <h4 class="text-center" style="color: black;">Submit a Leave Request</h4>
                <form action="submit_leave.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Leave Type:</label>
                        <select class="form-control" name="leave_type_id" id="leaveType" required>
                    <option value="" selected disabled>Select Type of Leave</option>
                    <?php while ($row = $leaveTypeResult->fetch_assoc()) { ?>
                        <option value="<?= $row['leave_type_id']; ?>" data-max="<?= $row['max_days']; ?>">
                            <?= htmlspecialchars($row['leave_name']) ?> (Max: <?= $row['max_days'] ?> days)
                        </option>
                    <?php } ?>
                </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Select Leave Dates:</label>
                        <input type="text" id="leave_dates" class="form-control" name="leave_dates" placeholder="Select your leave dates" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Total Days:</label>
                        <input type="number" class="form-control" id="total_days" name="total_days" placeholder="Total leave days" readonly>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Submit Leave Request</button>
                </form>
            </div>
        </div>

        <div class="col-md-8" style="flex: 1.2; max-width: 80%;">
            <div class="card2 card-custom" style="padding: 40px; min-height: 500px; width: 100%;">
                <h4 class="text-center" style="color: black;">Your Leave Requests</h4>
                <div class="table-responsive table-container">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Leave Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Total Days</th>
                                <th>Status</th>
                                <th>Request Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['leave_name']); ?></td>
                                    <td><?= $row['leave_start_date']; ?></td>
                                    <td><?= $row['leave_end_date']; ?></td>
                                    <td><?= $row['total_days']; ?></td>
                                    <td><?= $row['status']; ?></td>
                                    <td><?= $row['request_date']; ?></td>
                                </tr>
                            <?php } ?>
                            <?php if ($result->num_rows == 0) { ?>
                                <tr>
                                    <td colspan="6" class="text-center">No leave requests found.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const leaveType = document.getElementById("leaveType");
    const leaveDates = document.getElementById("leave_dates");
    const totalDaysInput = document.getElementById("total_days");

    let fp = flatpickr(leaveDates, {
        mode: "range",
        dateFormat: "Y-m-d",
        minDate: "today",
        onClose: function(selectedDates) {
            if (selectedDates.length === 2) {
                let maxDays = parseInt(leaveType.selectedOptions[0].dataset.max);
                let daysDiff = Math.ceil((selectedDates[1] - selectedDates[0]) / (1000 * 60 * 60 * 24)) + 1;

                if (daysDiff > maxDays) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Exceeded Maximum Days',
                        text: `You can only select up to ${maxDays} days.`,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Clear the field & reset total days
                        leaveDates.value = "";
                        totalDaysInput.value = "";
                        fp.clear();
                    });
                } else {
                    totalDaysInput.value = daysDiff;
                }
            } else {
                totalDaysInput.value = "";
            }
        }
    });
});
</script>

</body>
</html>
