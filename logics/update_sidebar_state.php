<?php
session_name('admin_session');
session_start();
include 'db_config.php'; // Include MySQLi database connection

header('Content-Type: application/json'); // Ensure JSON response

// Check if session exists
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "error" => "No admin session"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

// Ensure sidebarOn is received
if (!isset($data['sidebarOn'])) {
    echo json_encode(["success" => false, "error" => "sidebarOn not provided"]);
    exit;
}

$sidebarOn = ($data['sidebarOn'] == 1) ? 1 : 0; // Ensure it's either 0 or 1
$userId = $_SESSION['admin_id']; // Get the logged-in admin ID

// Check database connection
if (!$conn) {
    echo json_encode(["success" => false, "error" => "Database connection failed"]);
    exit;
}

// Prepare the SQL query
$query = "UPDATE admin SET sidebarOn = ? WHERE id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(["success" => false, "error" => "SQL Prepare Failed"]);
    exit;
}

$stmt->bind_param("is", $sidebarOn, $userId);
$success = $stmt->execute();

// Check execution
if (!$success) {
    echo json_encode(["success" => false, "error" => "SQL Execution Failed"]);
} else {
    echo json_encode(["success" => true, "sidebarOn" => $sidebarOn]);
}

// Close statement
$stmt->close();
?>
