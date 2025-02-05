<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* General page styling */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f4f7fc;
}

/* Sidebar styling */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 250px;
    background-color: #333;
    color: #fff;
    padding-top: 20px;
    padding-left: 20px;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
}

.sidebar a {
    color: white;
    display: block;
    text-decoration: none;
    padding: 10px 0;
}

.sidebar a:hover {
    background-color: #575757;
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

        /* Ensure the sidebar takes up appropriate space */
        .sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background-color: #343a40;
            padding-top: 20px;
            color: white;
        }

        .sidebar a {
            color: white;
            padding: 10px 15px;
            display: block;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #575757;
        }

        /* Container for the form and sidebar */
        .content-wrapper {
            margin-left: 250px; /* This will push the content beside the sidebar */
            padding: 20px;
        }

        /* Optional: Add a little spacing to the form */
        .form-container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
        }

        /* Adjust the form fields to be aligned nicely */
        .form-container .form-label {
            font-weight: 600;
        }

        .form-container .form-control {
            border-radius: 8px;
        }
    </style>
        
</head>
<body>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('open');
        }
    </script>
</body>
</html>

<body>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main content wrapper -->
    <div class="container mt-5">
        <h2>Employee Masterlist</h2>

        <!-- Employee table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Gender</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example employee data, replace with dynamic data from your database -->
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
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
