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
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center">HR Dashboard</h4>
        <a href="#"><i class="fas fa-users"></i> Employees</a>
        <a href="#"><i class="fas fa-wallet"></i> Payroll</a>
        <a href="#"><i class="fas fa-calendar-check"></i> Leave Requests</a>
        <a href="#"><i class="fas fa-file-alt"></i> Reports</a>
        <a href="#"><i class="fas fa-cog"></i> Settings</a>
        <a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Content -->
    <div class="content">
        <button class="sidebar-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
        <h2>Welcome to the HR Management System</h2>
        <p>Manage employees, payroll, leave requests, and generate reports efficiently.</p>
    </div>

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
