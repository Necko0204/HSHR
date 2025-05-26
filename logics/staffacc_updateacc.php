<?php
session_name('admin_session');
session_start();
include 'db_config.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"], $_POST["username"], $_POST["role"])) {
    $id = intval($_POST["id"]);
    $username = trim($_POST["username"]);
    $role = trim($_POST["role"]);

    if (empty($username) || empty($role)) {
        echo json_encode(["success" => false, "message" => "Fields cannot be empty"]);
        exit();
    }

    $stmt = $conn->prepare("UPDATE staff_accounts SET username = ?, role = ? WHERE id = ?");
    $stmt->bind_param("ssi", $username, $role, $id);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update staff details"]);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
?>
