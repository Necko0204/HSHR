<?php
require_once __DIR__ . '/../includes/admin_api.php';
include '../db_config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roleId = $_POST['role_id'] ?? '';

    if (empty($roleId)) {
        echo json_encode(['error' => 'Role ID is required.']);
        exit;
    }

    // Prepare SQL to reactivate the role
    $sql = "UPDATE roles SET status = 'Active' WHERE role_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $roleId);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Role reactivated successfully.']);
    } else {
        error_log('Role reactivation failed: ' . $stmt->error);
        echo json_encode(['error' => 'Role could not be reactivated.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
