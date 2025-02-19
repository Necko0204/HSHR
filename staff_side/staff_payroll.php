<?php
session_name('staff_session');
session_start();

include 'db_config.php';
include 'staff_helper.php';

// Redirect if not logged in
if (!isset($_SESSION['employee_id'])) {
    header("Location: index.php");
    exit();
}

$employee_id = $_SESSION['employee_id'];

// Get latest hourly rate
$query = "SELECT hourly_rate FROM salary_rates WHERE employee_id = ? ORDER BY effective_date DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$hourly_rate = $row['hourly_rate'] ?? 50; // Default ₱50 if no record

// Get total work hours per month in HH:MM:SS
$query = "
    SELECT 
        DATE_FORMAT(date, '%M %Y') AS month, 
        SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(time_out, time_in)))) AS total_hours
    FROM attendance 
    WHERE employee_id = ? 
    GROUP BY DATE_FORMAT(date, '%Y-%m') 
    ORDER BY MIN(date) DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

$payroll_data = [];
while ($row = $result->fetch_assoc()) {
    $month = $row['month'];
    $total_hours = $row['total_hours'] ?? '00:00:00';

    // Convert HH:MM:SS to total hours in decimal for salary computation
    list($hh, $mm, $ss) = explode(":", $total_hours);
    $decimal_hours = $hh + ($mm / 60) + ($ss / 3600);
    $computed_salary = $decimal_hours * $hourly_rate;

    $payroll_data[] = [
        'month' => $month,
        'total_hours' => $total_hours,
        'computed_salary' => $computed_salary
    ];
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Summary</title>
    <link rel="stylesheet" href="background.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color:rgb(78, 46, 46) !important;
        }
        .floating-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            overflow: hidden;
            background: linear-gradient(120deg, rgba(0, 0, 0, 0.8), rgba(167, 1, 1, 0.7)); /* Professional subtle background */
        }
    </style>    
</head>
<body>



    <div class="floating-container"></div> <!-- Floating squares container -->

    <!-- Back to Dashboard Button -->
    <div style="position: absolute; top: 20px; left: 20px; z-index: 1000;">
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to Dashboard
            </a>
    </div>
    
    <!-- Navigation Bar -->
    <div class="main-container">
        <?php include 'staff_navbar.php'; ?>
    </div>
    
    <div class="container mt-5">
        <h2 class="text-center mb-4">Monthly Payroll Summary</h2>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Month</th>
                    <th>Total Hours Worked</th>
                    <th>Computed Salary (₱)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($payroll_data)) : ?>
                    <?php foreach ($payroll_data as $row) : ?>
                        <tr>
                            <td><?= htmlspecialchars($row['month']) ?></td>
                            <td><?= htmlspecialchars($row['total_hours']) ?></td>
                            <td><?= number_format($row['computed_salary'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3" class="text-center">No payroll records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
