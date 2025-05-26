<?php
session_name('admin_session'); // Custom session name
session_start();
include 'db_config.php'; // Include MySQLi database connection

header('Content-Type: application/json'); // Ensure JSON response

// Check if admin_id is set in session
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "error" => "No admin session", "session" => $_SESSION]);
    exit;
}

$userId = $_SESSION['admin_id']; // Get admin ID

// Prepare the SQL query
$query = "SELECT sidebarOn FROM admin WHERE id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(["success" => false, "error" => "SQL Prepare Failed"]);
    exit;
}

$stmt->bind_param("s", $userId); // "i" for integer type
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Return JSON response
echo json_encode($data ?: ["success" => false, "error" => "No data found"]);

// Close statement
$stmt->close();
?>
