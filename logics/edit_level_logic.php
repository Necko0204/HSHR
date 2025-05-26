<?php
include '../db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $levelId = $_POST['levelId'];
    $levelName = $_POST['levelName'];

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE levels SET level_name = ? WHERE level_id = ?");
    $stmt->bind_param("sss", $levelName, $levelId);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Level updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating level"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>