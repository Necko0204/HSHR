<?php
session_name('staff_session');
session_start();

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include 'db_config.php';
include 'staff_helper.php';

if (!isset($_SESSION['employee_id']) || !in_array($_SESSION['role'], ['Staff', 'Intern'])) {
    header("Location: index.php");
    exit();
}
$sender_id = $_SESSION['employee_id'];
$sender_role = $_SESSION['role'];

// Fetch staff data
$staffData = getStaffData($sender_id);
if (!$staffData) {
    die("❌ Staff data not found.");
}

$employee_name = $staffData['firstname'] . ' ' . $staffData['lastname'];
$employee_id = $sender_id;

// Fetch leave requests
$leaveRequestQuery = "SELECT lr.*, lt.leave_name 
                      FROM leave_requests lr 
                      LEFT JOIN leave_types lt ON lr.leave_type_id = lt.leave_type_id 
                      WHERE lr.employee_id = ? 
                      ORDER BY lr.request_date DESC";
$stmt = $conn->prepare($leaveRequestQuery);
$stmt->bind_param("s", $employee_id);
$stmt->execute();
$result2 = $stmt->get_result();

// Fetch leave types for dropdown
$leaveTypeQuery = "SELECT leave_type_id, leave_name, max_days FROM leave_types ORDER BY leave_type_id";
$leaveTypeResult = $conn->query($leaveTypeQuery);

$leaveBalances = [];

$fetchBalancesQuery = "
    SELECT lt.leave_type_id AS leave_type_id, lt.leave_name, lt.max_days,
           COALESCE(lb.remaining_days, lt.max_days) + 
           COALESCE(SUM(CASE WHEN lr.status = 'Rejected' THEN lr.total_days ELSE 0 END), 0) 
           AS remaining_days,
           COALESCE(SUM(CASE WHEN lr.status != 'Rejected' THEN lr.total_days ELSE 0 END), 0) 
           AS total_used_days
    FROM leave_types lt
    LEFT JOIN leave_balances lb ON lt.leave_type_id = lb.leave_type_id AND lb.employee_id = ?
    LEFT JOIN leave_requests lr ON lt.leave_type_id = lr.leave_type_id AND lr.employee_id = ?
    GROUP BY lt.leave_type_id, lt.leave_name, lt.max_days, lb.remaining_days
";


$stmt = $conn->prepare($fetchBalancesQuery);
$stmt->bind_param("ss", $employee_id, $employee_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $remaining_days = max($row['max_days'] - $row['total_used_days'], 0);
    $leaveBalances[] = [
        'leave_name'      => $row['leave_name'],
        'remaining_days'  => $remaining_days
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reject_leave'])) {
    $leave_request_id = intval($_POST['leave_request_id']);

    // Fetch leave request details
    $query = "SELECT leave_type_id, total_days, employee_id FROM leave_requests WHERE leave_request_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $leave_request_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $leave = $result->fetch_assoc();

    if ($leave) {
        $leave_type_id = $leave['leave_type_id'];
        $total_days = $leave['total_days'];
        $employee_id = $leave['employee_id'];

        // Update leave request status to rejected
        $updateQuery = "UPDATE leave_requests SET status = 'Rejected' WHERE leave_request_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("i", $leave_request_id);
        $stmt->execute();

        // Restore leave balance
        $checkBalanceQuery = "SELECT remaining_days FROM leave_balances WHERE employee_id = ? AND leave_type_id = ?";
        $stmt = $conn->prepare($checkBalanceQuery);
        $stmt->bind_param("si", $employee_id, $leave_type_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update leave balance
            $updateBalanceQuery = "UPDATE leave_balances SET remaining_days = remaining_days + ? WHERE employee_id = ? AND leave_type_id = ?";
            $stmt = $conn->prepare($updateBalanceQuery);
            $stmt->bind_param("isi", $total_days, $employee_id, $leave_type_id);
            $stmt->execute();
        } else {
            // If no balance record exists, insert a new one
            $insertBalanceQuery = "INSERT INTO leave_balances (employee_id, leave_type_id, remaining_days) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertBalanceQuery);
            $stmt->bind_param("sii", $employee_id, $leave_type_id, $total_days);
            $stmt->execute();
        }

        echo "<script>
                Swal.fire('Success', 'Leave request rejected, balance updated.', 'success')
                    .then(() => window.location.reload());
              </script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../images/asdasdasd123123123123123.jpg">
    <title>Leave Requests</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
    .card {
        border-radius: 1.5rem !important;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        transition: transform 0.15s;
    }
    .card:hover {
        transform: translateY(-4px) scale(1.01);
        box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.12);
    }
    .table th, .table td {
        vertical-align: middle;
    }
    .form-label {
        color: #374151;
        font-size: 1rem;
    }
    .form-select, .form-control {
        background: #f8fafc;
        border: 1px solid #d1d5db;
        font-size: 1rem;
    }
    .form-select:focus, .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.15);
    }
    .btn-primary {
        background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(90deg, #4f46e5 0%, #2563eb 100%);
    }
    @media (max-width: 991px) {
        .content-wrapper {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        .row.g-5 {
            gap: 2rem 0.5rem !important;
        }
    }
    @media (max-width: 767px) {
        .content-wrapper {
            padding-left: 0.2rem;
            padding-right: 0.2rem;
        }
        .card {
            border-radius: 1rem !important;
        }
    }
</style>
</head>
<body style="overflow: hidden;">

 <!-- Navigation Bar (unchanged) -->
 <div class="main-container">
        <?php include 'staff_navbar.php'; ?>
    </div>

<!-- Back to Dashboard Button -->
<div style="position: absolute; top: 7px; left: 20px; z-index: 1000;">
    <a href="dashboard.php" class="btn btn-secondary">
        <i class="fa fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

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


<div class="container content-wrapper py-5">
    <h2 class="mb-5 text-center fw-bold" style="color: #2d3748; letter-spacing: 1px; text-shadow: 0 2px 8px rgba(0,0,0,0.08);">
        <i class="fa-solid fa-calendar-check me-2"></i>Leave Requests
    </h2>
    <div class="row g-5 justify-content-center align-items-stretch">
        <!-- Leave Balance (Left Side) -->
        <div class="col-lg-3 col-md-5 mb-4 mb-lg-0">
            <div class="card shadow-lg border-0 h-100 rounded-4" style="background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);">
                <div class="card-body d-flex flex-column">
                    <h4 class="text-center mb-4 fw-semibold" style="color: #2d3748;">
                        <i class="fa-solid fa-wallet me-2"></i>Leave Balance
                    </h4>
                    <div class="table-responsive flex-grow-1">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-primary">
                                <tr>
                                    <th>Leave Type</th>
                                    <th class="text-end">Remaining</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($leaveBalances)) { ?>
                                    <?php foreach ($leaveBalances as $balance) { ?>
                                        <tr>
                                            <td><?= htmlspecialchars($balance['leave_name']); ?></td>
                                            <td class="text-end">
                                                <?= $balance['remaining_days'] > 0 ? '<span class="fw-bold text-success">'.$balance['remaining_days'].'</span>' : '<span class="fw-bold text-danger">No leave left</span>'; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">No leave balance available.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leave Requests Table (Center) -->
        <div class="col-lg-5 col-md-7 mb-4 mb-lg-0">
            <div class="card shadow-lg border-0 h-100 rounded-4" style="background: linear-gradient(135deg, #f0fdf4 0%, #f8fafc 100%);">
                <div class="card-body d-flex flex-column">
                    <h4 class="text-center mb-4 fw-semibold" style="color: #2d3748;">
                        <i class="fa-solid fa-list-check me-2"></i>Your Leave Requests
                    </h4>
                    <div class="table-responsive flex-grow-1">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-success">
                                <tr>
                                    <th>Type</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th class="text-center">Days</th>
                                    <th>Status</th>
                                    <th>Requested</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result2->num_rows > 0) { ?>
                                    <?php while ($row2 = $result2->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row2['leave_name']); ?></td>
                                            <td><?= date('d M Y', strtotime($row2['leave_start_date'])); ?></td>
                                            <td><?= date('d M Y', strtotime($row2['leave_end_date'])); ?></td>
                                            <td class="text-center"><?= $row2['total_days']; ?></td>
                                            <td>
                                                <?php
                                                    $status = $row2['status'];
                                                    $badge = [
                                                        'Pending' => 'warning',
                                                        'Approved' => 'success',
                                                        'Rejected' => 'danger'
                                                    ][$status] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?= $badge; ?> px-3 py-2 fs-6"><?= $status; ?></span>
                                            </td>
                                            <td><?= date('d M Y', strtotime($row2['request_date'])); ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No leave requests found.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Leave Request Form (Right Side) -->
        <div class="col-lg-4 col-md-8">
            <div class="card shadow-lg border-0 h-100 rounded-4" style="background: linear-gradient(135deg, #fff7ed 0%, #f8fafc 100%);">
                <div class="card-body d-flex flex-column">
                    <h4 class="text-center mb-4 fw-semibold" style="color: #2d3748;">
                        <i class="fa-solid fa-paper-plane me-2"></i>Submit a Leave Request
                    </h4>
                    <form action="submit_leave.php" method="POST" class="flex-grow-1 d-flex flex-column justify-content-between">
                        <div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Leave Type</label>
                                <select class="form-select rounded-pill px-3 py-2" name="leave_type_id" id="leaveType" required>
                                    <option value="" selected disabled>Select Type of Leave</option>
                                    <?php while ($row2 = $leaveTypeResult->fetch_assoc()) { ?>
                                        <option value="<?= $row2['leave_type_id']; ?>" data-max="<?= $row2['max_days']; ?>">
                                            <?= htmlspecialchars($row2['leave_name']); ?> 
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Select Leave Dates</label>
                                <input type="text" id="leave_dates" class="form-control rounded-pill px-3 py-2" name="leave_dates" placeholder="Select your leave dates" required autocomplete="off">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Total Days</label>
                                <input type="number" class="form-control rounded-pill px-3 py-2" id="total_days" name="total_days" placeholder="Total leave days" readonly>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mt-2 rounded-pill" style="letter-spacing: 1px; font-size: 1.1rem;">
                            <i class="fa-solid fa-paper-plane me-1"></i>Submit Leave Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>

document.addEventListener("DOMContentLoaded", function () {
    const leaveTypeSelect = document.getElementById("leaveType");
    const leaveDatesInput = document.getElementById("leave_dates");
    const totalDaysInput = document.getElementById("total_days");

    let maxDays = 0;
    let leavePicker;

    function initializeFlatpickr() {
        if (leavePicker) {
            leavePicker.destroy(); // Destroy existing instance before re-initializing
        }

        leavePicker = flatpickr(leaveDatesInput, {
            mode: "range",
            dateFormat: "Y-m-d",
            minDate: "today",
            onClose: function (selectedDates) {
                if (selectedDates.length < 2) return;

                let startDate = selectedDates[0];
                let endDate = selectedDates[1];
                let diffTime = Math.abs(endDate - startDate);
                let totalDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

                if (totalDays > maxDays) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Exceeding Maximum Leave Days',
                        text: `You can only take a maximum of ${maxDays} days for this leave type.`,
                        confirmButtonColor: '#d33'
                    }).then(() => {
                        leavePicker.clear(); // Reset selection if exceeded
                        totalDaysInput.value = ""; // Clear total days input
                    });
                } else {
                    totalDaysInput.value = totalDays;
                }
            }
        });
    }

    leaveTypeSelect.addEventListener("change", function () {
        maxDays = parseInt(this.options[this.selectedIndex].getAttribute("data-max"));
        leavePicker.clear();
        totalDaysInput.value = "";
        initializeFlatpickr();
    });

    initializeFlatpickr();
});
</script>

<script>
document.querySelector('form').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('submit_leave.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Leave Submitted!',
                text: 'Your leave request has been submitted successfully.',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                location.reload(); // Refresh the page to show new leave request
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: data.title || 'Error',
                text: data.message || 'Something went wrong.',
                confirmButtonColor: '#d33'
            });
        }
    })
    .catch(err => {
        Swal.fire({
            icon: 'error',
            title: 'Request Failed',
            text: 'Could not send leave request.',
            confirmButtonColor: '#d33'
        });
        console.error(err);
    });
});
</script>

</body>
</html>