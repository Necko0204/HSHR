<?php
session_start();
include 'db_config.php';
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

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: white;
            color: black;
            min-height: 100vh;
        }

        .wrapper {
            margin-left: 270px; /* Adjust based on sidebar width */
            padding: 20px;
            max-width: calc(100% - 270px);
        }

        @media (max-width: 768px) {
            .wrapper {
                margin-left: 0;
                max-width: 100%;
            }
        }

        /* Table adjustments */
        .table-responsive {
            overflow-x: auto;
        }

        /* Status badge styling */
        .badge-active { background-color: green; color: white; padding: 5px; border-radius: 5px; }
        .badge-inactive { background-color: red; color: white; padding: 5px; border-radius: 5px; }

        /* Button styles */
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
        .hidden-id {
            display: none;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <main class="wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <h2 class="mb-0">Employee Masterlist</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                Add Employee
            </button>
        </div>

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
