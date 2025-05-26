<?php
session_name('admin_session');
session_start();
include 'db_config.php';

header('Content-Type: application/json'); // Ensure response is JSON

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['leave_id'], $_POST['action'])) {
    $leave_id = intval($_POST['leave_id']);
    $action = $_POST['action'];

    $status = ($action === 'approve') ? 'Approved' : 'Rejected';

    $stmt = $conn->prepare("UPDATE leave_requests SET status = ? WHERE leave_id = ?");
    $stmt->bind_param("si", $status, $leave_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Leave request successfully updated."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update leave request."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
exit();
