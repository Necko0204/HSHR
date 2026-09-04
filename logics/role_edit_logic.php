<?php
require_once __DIR__ . '/../includes/admin_api.php';
include 'db_config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roleId = $_POST['role_id'] ?? '';
    $roleName = $_POST['roleName'] ?? '';
    $roleDescription = $_POST['roleDescription'] ?? '';

    if (empty($roleId) || empty($roleName)) {
        echo json_encode(['status' => 'error', 'message' => 'Role ID and name are required.']);
        exit;
    }

    // Prepare the UPDATE statement
    $stmt = $conn->prepare("UPDATE roles SET role_name = ?, description = ? WHERE role_id = ?");
    $stmt->bind_param("sss", $roleName, $roleDescription, $roleId);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Role updated successfully.']);
        } else {
            echo json_encode(['status' => 'info', 'message' => 'No changes made or role not found.']);
        }
    } else {
        error_log('Role update failed: ' . $stmt->error);
        echo json_encode(['status' => 'error', 'message' => 'Role could not be updated.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
