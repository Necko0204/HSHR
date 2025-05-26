<?php
session_name('admin_session');
session_start();
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
        echo json_encode(['error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
