<?php
require_once __DIR__ . '/admin_session.php';
if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    exit('Authentication required.');
}
include 'db_config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Check if ID exists in attendance
    $query = "SELECT a.id, e.firstname, a.time_in, 'attendance' AS type
              FROM attendance a
              JOIN employees e ON a.employee_id = e.id
              WHERE a.id = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode([
            "success" => true,
            "id" => $row['id'],
            "firstname" => $row['firstname'],
            "time_in" => $row['time_in'], // Keeping consistent for attendance
            "type" => $row['type']
        ]);
        exit;
    }
    mysqli_stmt_close($stmt); // Close first statement

    // Check if ID exists in leave_requests
    $query = "SELECT l.leave_id AS id, e.firstname, l.request_date AS time_in, 'leave' AS type
              FROM leave_requests l
              JOIN employees e ON l.employee_id = e.id
              WHERE l.leave_id = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode([
            "success" => true,
            "id" => $row['id'],
            "firstname" => $row['firstname'],
            "time_in" => $row['time_in'], // Keeping consistent for leave_requests
            "type" => $row['type']
        ]);
        exit;
    }

    // If no records found
    echo json_encode(["success" => false, "message" => "Notification not found."]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}

mysqli_close($conn);
?>
