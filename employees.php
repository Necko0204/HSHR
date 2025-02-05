<?php
session_start();
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
/* Wrapper for main content */
.wrapper {
    margin-left: 270px; /* Adjust based on your sidebar width */
    padding: 20px;
    max-width: calc(100% - 270px);
}

/* Fix alignment on smaller screens */
@media (max-width: 768px) {
    .wrapper {
        margin-left: 0;
        max-width: 100%;
    }
}

/* Content wrapper for employee details */
.container {
    margin-left: 270px;
    padding: 20px;
}

/* Form wrapper card styling */
form {
    background-color: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    font-size: 2rem;
    margin-bottom: 20px;
    color: #333;
}

/* Card styling for each section */
.mb-3 {
    margin-bottom: 20px;
}

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

/* Adjusting layout of form elements for better spacing */
.mb-3 {
    width: 100%;
}

@media (max-width: 768px) {
    .container {
        margin-left: 0;
    }
    
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
        padding: 10px;
    }
}
    </style>
</head>
<body>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    <!-- Main Content Wrapper -->
    <main class="wrapper">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Employee Masterlist</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">Add Employee</button>
        </div>

        <!-- Employee table -->
        <div class="card">
            <div class="card-header">Employee List</div>
            <div class="card-body">
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Gender</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>001</td>
                            <td>John Doe</td>
                            <td>Male</td>
                            <td><a href="employee_details.php" class="btn btn-info">View Details</a></td>
                        </tr>
                        <tr>
                            <td>002</td>
                            <td>Jane Smith</td>
                            <td>Female</td>
                            <td><a href="employee_details.php" class="btn btn-info">View Details</a></td>
                        </tr>
                    </tbody>
                </table>
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
