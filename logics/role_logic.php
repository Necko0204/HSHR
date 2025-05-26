<?php
session_name('admin_session');
session_start();
include 'db_config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roleName = $_POST['roleName'] ?? '';
    $roleDescription = $_POST['roleDescription'] ?? '';

    if (empty($roleName) || empty($roleDescription)) {
        echo json_encode(['error' => 'Role name and description are required.']);
        exit;
    }

    // Fetch the last inserted role ID
    $sql = "SELECT role_id FROM roles ORDER BY created_at DESC LIMIT 1";
    $result = $conn->query($sql);
    $lastId = "HSHI-ROLE20250000"; // Default ID if no roles exist

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastId = $row['role_id'];
    }

    // Extract the numeric part and increment it
    $numericPart = (int)substr($lastId, 10);
    $newNumericPart = str_pad($numericPart + 1, 8, '0', STR_PAD_LEFT);

    // Generate the new role ID
    $newRoleId = "HSHI-ROLE" . $newNumericPart;

    // Insert the new role into the database
    $stmt = $conn->prepare("INSERT INTO roles (role_id, role_name, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $newRoleId, $roleName, $roleDescription);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'New role added successfully', 'role_id' => $newRoleId]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
