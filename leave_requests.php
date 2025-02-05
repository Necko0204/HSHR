<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Requests</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<style>

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
<body>
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main content wrapper -->
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Leave Requests</h2>
        </div>

        <!-- Leave Requests table -->
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Leave ID</th>
                    <th>Employee Name</th>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Total Days</th>
                    <th>Status</th>
                    <th>Request Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>101</td>
                    <td>John Doe</td>
                    <td>Vacation</td>
                    <td>2024-02-10</td>
                    <td>2024-02-15</td>
                    <td>5</td>
                    <td>Pending</td>
                    <td>2024-02-05</td>
                    <td>
                        <button class="btn btn-success">Approve</button>
                        <button class="btn btn-danger">Reject</button>
                    </td>
                </tr>
                <tr>
                    <td>102</td>
                    <td>Jane Smith</td>
                    <td>Sick Leave</td>
                    <td>2024-02-12</td>
                    <td>2024-02-14</td>
                    <td>2</td>
                    <td>Pending</td>
                    <td>2024-02-06</td>
                    <td>
                        <button class="btn btn-success">Approve</button>
                        <button class="btn btn-danger">Reject</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
