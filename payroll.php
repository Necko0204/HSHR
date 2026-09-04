<?php
require_once __DIR__ . '/includes/admin_page.php';
include 'includes/breadcrumb.php';
include 'db_config.php';
include 'helper.php';

$employees = getEmployees(); // Fetch employees from the database

// Debug: Check if session is properly set
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/asdasdasd123123123123123.jpg">
    <title>Holy Spirit Human Resource</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="background.css">
</head>
    <body>
<!-- Sidebar & Navbar in a separate container -->
<div class="main-container">
    <?php include 'sidebar.php'; ?>
    </div>
    <div class="content-container">
        <?php include 'nav_header.php'; ?>
        </div>

       <!-- Main Content Wrapper -->
       <main class="wrapper">
                <?php
                echo generateBreadcrumb();
                ?>
        <div class="d-flex justify-content-start align-items-center mb-3">
            <h2 class="fw-bold mb-0"><i class="fa fa-users"></i> Employee Payroll</h2>
            <!-- <button class="btn btn-primary ms-auto d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#CalculatePayrollModal">
                <i class="fa fa-calculator"></i> Calculate Payroll
            </button> -->
        </div>
        <!-- Payroll Table -->
        <div class="card shadow-lg border-1 rounded-3">
            <div class="card-header bg-gradient-primary text-black d-flex justify-content-between align-items-center">
                <!-- Search Bar -->
                <div class="d-flex align-items-center">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search..." style="width: 150px;">
                </div>
                <!-- Archives Button -->
                <button type="button" class="btn btn-secondary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#ArchivesModal">
                    <i class="fa fa-folder"></i> <span>Archives</span>
                </button>
            </div>
            <div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
                <style>
                    #payrollTable th, #payrollTable td {
                        min-width: 150px;
                        max-width: 150px;
                        width: 150px;
                        text-align: left;
                        vertical-align: middle;
                    }
                    .employee-id-col {
                        display: none;
                    }
                </style>
                <?php
                // Fetch all employees for payroll calculation
                $sql = "SELECT id, firstname, lastname, salary FROM employees";
                $result = $conn->query($sql);
                ?>
                <div style="max-height: 530px; overflow-y: auto;">
                    <table class="table table-borderless table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="employee-id-col">Employee ID</th>
                                <th>Name</th>
                                <th>Monthly Salary</th>
                                <th>Monthly Required Hours</th>
                                <th>Hourly Rate</th>
                                <th>Month</th>
                                <th>Total Worked Hours</th>
                                <th>Computed Salary</th>
                            </tr>
                        </thead>
                        <tbody id="payrollTable">
                        <?php
                        if ($result && $result->num_rows > 0) {
                            while ($emp = $result->fetch_assoc()) {
                                $employee_id = $emp['id'];
                                $fullname = htmlspecialchars($emp['lastname'] . ', ' . $emp['firstname']);
                                $monthly_salary = floatval($emp['salary']);

                                // Get weekly required hours
                                $stmt = $conn->prepare("SELECT COALESCE(SUM(required_hours),0) AS weekly_required FROM work_schedules WHERE employee_id = ?");
                                $stmt->bind_param("s", $employee_id);
                                $stmt->execute();
                                $res = $stmt->get_result()->fetch_assoc();
                                $weekly_required = floatval($res['weekly_required']);
                                $stmt->close();

                                // Calculate monthly required hours and hourly rate
                                $monthly_required_hours = $weekly_required * 4;
                                if ($monthly_required_hours > 0 && $monthly_salary > 0) {
                                    $hourly_rate = $monthly_salary / $monthly_required_hours;
                                } else {
                                    $hourly_rate = 50; // fallback ₱50/hr
                                }

                                // Pull attendance and build payroll_data for each month
                                $query = "
                                  SELECT
                                    DATE_FORMAT(date, '%M %Y') AS month,
                                    SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(time_out, time_in)))) AS total_hours
                                  FROM attendance
                                  WHERE employee_id = ?
                                  GROUP BY DATE_FORMAT(date, '%Y-%m')
                                  ORDER BY MIN(date) DESC
                                ";
                                $stmt = $conn->prepare($query);
                                $stmt->bind_param("s", $employee_id);
                                $stmt->execute();
                                $attendance_result = $stmt->get_result();

                                if ($attendance_result->num_rows > 0) {
                                    while ($row = $attendance_result->fetch_assoc()) {
                                        $month = $row['month'];
                                        $hms   = $row['total_hours'] ?? '00:00:00';
                                        list($H, $M, $S) = explode(':', $hms);
                                        $decimal_hours = $H + ($M/60) + ($S/3600);

                                        // Compute pay using dynamic hourly_rate
                                        $computed_salary = $decimal_hours * $hourly_rate;
                                        ?>
                                        <tr>
                                            <td class="employee-id-col"><?= htmlspecialchars($employee_id) ?></td>
                                            <td><?= $fullname ?></td>
                                            <td><?= number_format($monthly_salary, 2) ?></td>
                                            <td><?= number_format($monthly_required_hours, 2) ?></td>
                                            <td><?= number_format($hourly_rate, 2) ?></td>
                                            <td><?= htmlspecialchars($month) ?></td>
                                            <td><?= htmlspecialchars($hms) ?> (<?= number_format($decimal_hours, 2) ?> hrs)</td>
                                            <td><?= number_format($computed_salary, 2) ?></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    // No attendance for this employee
                                    ?>
                                    <tr>
                                        <td class="employee-id-col"><?= htmlspecialchars($employee_id) ?></td>
                                        <td><?= $fullname ?></td>
                                        <td><?= number_format($monthly_salary, 2) ?></td>
                                        <td><?= number_format($monthly_required_hours, 2) ?></td>
                                        <td><?= number_format($hourly_rate, 2) ?></td>
                                        <td colspan="3" class="text-muted">No attendance records</td>
                                    </tr>
                                    <?php
                                }
                                $stmt->close();
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php $conn->close(); ?>
            </div>
        </div>

        <!-- Calculate Payroll Modal -->
        <div class="modal fade" id="CalculatePayrollModal" tabindex="-1" aria-labelledby="CalculatePayrollModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="CalculatePayrollModalLabel">Calculate Payroll</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form id="payrollForm">
                    <div class="mb-3">
                    <label for="payPeriod" class="form-label">Pay Period</label>
                    <input type="text" class="form-control" id="payPeriod" required>
                    </div>
                    <div class="mb-3">
                    <label for="employeeSelect" class="form-label">Select Employee</label>
                    <select class="form-select" id="employeeSelect" required>
                        <option value="" disabled selected>Choose an Employee</option>
                        <?php foreach ($employees as $employee): ?>
                        <option value="<?= $employee['id']; ?>">
                            <?= htmlspecialchars($employee['lastname'] . ', ' . $employee['firstname']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    </div>
                    <div class="mb-3">
                    <label for="hoursWorked" class="form-label">Hours Worked</label>
                    <input type="number" class="form-control" id="hoursWorked" min="0" required>
                    </div>
                    <div class="mb-3">
                    <label for="hourlyRate" class="form-label">Hourly Rate ($)</label>
                    <input type="number" class="form-control" id="hourlyRate" min="0" step="0.01" required>
                    </div>
                    <div class="mb-3">
                    <label for="totalPay" class="form-label">Total Pay ($)</label>
                    <input type="text" class="form-control" id="totalPay" readonly>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="calculatePayroll()">
                    <i class="fa fa-calculator"></i> Calculate
                    </button>
                    <button type="submit" class="btn btn-success">
                    <i class="fa fa-check"></i> Submit
                    </button>
                </form>
                </div>
            </div>
            </div>
        </div>
        </main>
        <!-- Archives Modal -->
        <div class="modal fade" id="ArchivesModal" tabindex="-1" aria-labelledby="ArchivesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ArchivesModalLabel">Archives</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                <i class="fas fa-times"></i> Close
                </button>
            </div>
            </div>
        </div>
        </div>

    <!-- Bootstrap JS -->
    <script src="background.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "timeOut": "1000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#payPeriod", {
                mode: "range",
                dateFormat: "Y-m-d",
                maxDate: new Date().fp_incr(30) // Limit range to 1 month
            });
        });

        document.getElementById("searchInput").addEventListener("keyup", function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#payrollTable tr");

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});
    </script>
</body>
</html>
