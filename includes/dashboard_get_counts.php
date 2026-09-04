<?php
require_once __DIR__ . '/admin_api.php';
require '../db_config.php'; // Make sure to include your database connection

header('Content-Type: application/json');

// Fetch current total staff
$query = "SELECT COUNT(id) AS total_staff FROM employees";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$total_staff = $row['total_staff'];

// Fetch current active teachers
$query = "SELECT COUNT(employee_id) AS active_teachers FROM staff_accounts WHERE status = 'active'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$active_teachers = $row['active_teachers'];

// Get today's and yesterday's dates
$today = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));

// Fetch yesterday's total staff and active teachers
$query = "SELECT total_staff, active_teachers FROM historical_data WHERE date = '$yesterday'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$yesterday_total_staff = $row ? $row['total_staff'] : 0;
$yesterday_active_teachers = $row ? $row['active_teachers'] : 0;

// Calculate trends
$total_staff_trend = $total_staff - $yesterday_total_staff;
$active_teachers_trend = $active_teachers - $yesterday_active_teachers;

// Calculate percentage change
$total_staff_percentage = ($yesterday_total_staff > 0) ? round(($total_staff_trend / $yesterday_total_staff) * 100, 2) : 0;
$active_teachers_percentage = ($yesterday_active_teachers > 0) ? round(($active_teachers_trend / $yesterday_active_teachers) * 100, 2) : 0;

// Insert today's data if not already inserted
$query = "SELECT COUNT(*) AS count FROM historical_data WHERE date = '$today'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row['count'] == 0) {
    $query = "INSERT INTO historical_data (date, total_staff, active_teachers)
              VALUES ('$today', $total_staff, $active_teachers)";
    mysqli_query($conn, $query);
}

// Return JSON response
echo json_encode([
    'total_staff' => [
        'count' => $total_staff,
        'change' => $total_staff_percentage
    ],
    'active_teachers' => [
        'count' => $active_teachers,
        'change' => $active_teachers_percentage
    ]
]);
?>
