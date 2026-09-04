<?php
require_once __DIR__ . '/admin_session.php';
require_once __DIR__ . '/db_config.php';

header('Content-Type: application/json'); // Ensure JSON response

// Check if admin_id is set in session
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "error" => "Authentication required"]);
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
