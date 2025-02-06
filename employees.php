<?php
session_name('admin_session');
session_start();

include 'db_config.php';

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
    <title>HR Management - School</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="background.css">

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
            
            </style>
        </head>
<body>
    <!-- Sidebar & Navbar-->
    <?php include 'sidebar.php'; ?>
    <?php include 'nav_header.php'; ?>

<!-- Main Content Wrapper -->
<main class="wrapper">
<section class="content">
    <div class="d-flex justify-content-start align-items-center">
        <h2 class="fw-bold text-dark mb-0">Employee Masterlist</h2>
        <button class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
            Add Employee
        </button>
    </div>
</section>

        <!-- Employee Table -->
        <div class="card">
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
                            <th>Actions</th>
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
                                    <a href="employee_details.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">View Details</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php $conn->close(); ?>
            </div>
        </div>

        <!-- Add Employee Modal -->
        <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEmployeeModalLabel">Add Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="employeeName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="employeeName" required>
                            </div>
                            <div class="mb-3">
                                <label for="employeeGender" class="form-label">Gender</label>
                                <select class="form-control" id="employeeGender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
        <!-- Bootstrap JS -->
         <script src="background.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>


</html>
