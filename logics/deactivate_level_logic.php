<?php
include '../db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $levelId = $_POST['level_id'];

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE levels SET status = 'Inactive' WHERE level_id = ?");
    $stmt->bind_param("s", $levelId);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Level deactivated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error deactivating level"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>