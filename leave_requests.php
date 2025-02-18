<?php
session_name('admin_session');
session_start();

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
        <div class="d-flex justify-content-start align-items-center">
            <h2 class="fw-bold mb-0">Employee Leave Request</h2>
        </div>

    <!-- Employee Table -->
    <div class="card shadow-lg border-0 rounded-3">
    <div class="card-header bg-gradient-primary text-black">
                <h4 class="mb-0">Employee List</h4>
            </div>
        <div class="card-body table-responsive p-0">
            <?php
            $sql = "SELECT id, lastname, firstname, gender, email1, status FROM employees";
            $result = $conn->query($sql);
            ?>

    <table class="table table-borderless table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th class="hidden-id">ID</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Gender</th>
                <th>Email</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="hidden-id"><?= htmlspecialchars($row["id"]) ?></td>
                    <td><?= htmlspecialchars($row["lastname"]) ?></td>
                    <td><?= htmlspecialchars($row["firstname"]) ?></td>
                    <td><?= htmlspecialchars($row["gender"]) ?></td>
                    <td><?= htmlspecialchars($row["email1"]) ?></td>
                    <td>
                        <span class="<?= $row["status"] == 'Active' ? 'badge-active' : 'badge-inactive' ?>">
                            <?= htmlspecialchars($row["status"]) ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-success btn-sm">Accept</button>
                        <button class="btn btn-danger btn-sm">Reject</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
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
                        <button type="button" class="btn btn-primary" onclick="calculatePayroll()">Calculate</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>


    <!-- Bootstrap JS -->
    <script src="background.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#payPeriod", {
                mode: "range",
                dateFormat: "Y-m-d",
                maxDate: new Date().fp_incr(30) // Limit range to 1 month
            });
        });
    </script>
</body>
</html>
