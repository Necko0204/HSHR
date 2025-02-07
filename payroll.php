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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="background.css">
</head>
<style>
        body {
            font-family: 'Poppins', sans-serif;
            background: white;
            color: black;
            min-height: 100vh;
            margin: 0; /* Reset margin to ensure no unwanted space */
        }

        /* Wrapper for main content */
        .wrapper {
            margin-left: 270px; /* Sidebar width */
            padding: 20px;
            max-width: calc(100% - 270px); /* Adjust width to subtract sidebar width */
            transition: all 0.3s ease; /* Smooth transition */
        }

            /* Adjust wrapper when sidebar is hidden on small screens */
            @media (max-width: 768px) {
                .wrapper {
                    margin-left: 0;
                    max-width: 100%;
                    padding: 15px; /* Adjust padding for smaller screens */
                }
            }

            /* Content section styling */
            .content {
                margin-top: 20px; /* Ensure spacing between navbar and content */
            }

            h2 {
                font-size: 2rem;
                margin-bottom: 20px;
                color: #333;
            }

            /* Table and card styling */
            .card {
                border: 1px solid #ddd;
                border-radius: 10px;
                margin-bottom: 20px;
                padding: 20px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
                background-color: #fff;
            }

            .card-header {
                font-weight: bold;
                font-size: 1.2rem;
                background-color: #f8f9fa;
                padding: 10px;
                border-bottom: 1px solid #ddd;
            }

            .card-body {
                padding: 20px 0;
            }

            .card-body .form-label {
                font-weight: 500;
                color: #333;
            }

            /* Form styling */
            form {
                background-color: white;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }

            .form-control {
                border-radius: 8px;
                border: 1px solid #ddd;
                padding: 10px;
                margin-bottom: 15px;
            }

            .form-control:focus {
                border-color: #6c63ff;
                box-shadow: 0 0 5px rgba(108, 99, 255, 0.2);
            }

            /* Button styling */
            .btn-primary {
                background-color: #6c63ff;
                border-color: #6c63ff;
                padding: 10px 20px;
                border-radius: 8px;
            }

            .btn-primary:hover {
                background-color: #5748d0;
                border-color: #5748d0;
            }

            .mb-3 {
                margin-bottom: 20px;
            }

            /* Adjust card elements */
            .card-body {
                padding: 15px;
            }

            /* Additional layout adjustments for smaller screens */
            @media (max-width: 768px) {
                .card {
                    padding: 15px;
                }
                .btn-primary {
                    padding: 8px 16px;
                }
                .form-control {
                    padding: 8px;
                }
            }
            .hidden-id {
            display: none;
            }
            </style>
    <body>
        <!-- Sidebar & Navbar-->
        <?php include 'sidebar.php'; ?>
        <?php include 'nav_header.php'; ?>

   <!-- Main Content Wrapper -->
<main class="wrapper">
    <section class="content" data-aos="fade-left">
        <div class="d-flex justify-content-start align-items-center">
            <h2 class="fw-bold text-dark mb-0">Employee Payroll</h2>
            <button class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#CalculatePayrollModal">
                Calculate Payroll
            </button>
        </div>
    </section>

    <!-- Employee Table -->
    <div class="card" data-aos="fade-left" data-aos-delay="200">
        <div class="card-header">Employee List</div>
        <div class="card-body table-responsive">
            <?php
            $sql = "SELECT id, lastname, firstname, gender, email1, status FROM employees";
            $result = $conn->query($sql);
            ?>

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th class="hidden-id">ID</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Gender</th>
                        <th>Email</th>
                        <th>Status</th>
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
