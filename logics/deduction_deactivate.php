<?php
require_once __DIR__ . '/../includes/admin_api.php';
require 'db_config.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';

    // Validate input
    $id = trim($id); // Sanitize the input

    if (empty($id)) {
        echo json_encode(["success" => false, "message" => "Invalid deduction ID"]);
        exit;
    }

    // Update the deduction status to "inactive"
    $sql = "UPDATE deductions SET status = 'Inactive', updated_at = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id); // Using "s" since id is a string

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Deduction deactivated successfully"]);
    } else {
        error_log('Deduction deactivation failed: ' . $conn->error);
        echo json_encode(["success" => false, "message" => "Failed to deactivate deduction."]);
    }

    $stmt->close();
    $conn->close();
}
?>
