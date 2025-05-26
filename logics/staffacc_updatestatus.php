<?php
session_name('admin_session');
session_start();
include 'db_config.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"], $_POST["action"])) {
    $staff_id = intval($_POST["id"]); // This is staff_accounts.id
    $action = strtolower(trim($_POST["action"]));

    // Determine the new status
    $newStatus = ($action === "deactivate") ? "Inactive" : "Active";

    // Start transaction to ensure both updates succeed together
    $conn->begin_transaction();

    try {
        // Get the corresponding employee_id from staff_accounts
        $stmt1 = $conn->prepare("SELECT employee_id FROM staff_accounts WHERE id = ?");
        $stmt1->bind_param("i", $staff_id);
        $stmt1->execute();
        $stmt1->bind_result($employee_id);
        $stmt1->fetch();
        $stmt1->close();

        if (!$employee_id) {
            throw new Exception("Employee ID not found");
        }

        // Update staff_accounts table
        $stmt2 = $conn->prepare("UPDATE staff_accounts SET status = ? WHERE id = ?");
        $stmt2->bind_param("si", $newStatus, $staff_id);
        $stmt2->execute();
        $stmt2->close();

        // Update employees table using the retrieved employee_id
        $stmt3 = $conn->prepare("UPDATE employees SET status = ? WHERE id = ?");
        $stmt3->bind_param("ss", $newStatus, $employee_id);
        $stmt3->execute();
        $stmt3->close();

        // Commit transaction
        $conn->commit();

        echo json_encode(["success" => true, "newStatus" => $newStatus]);
    } catch (Exception $e) {
        // Rollback transaction on failure
        $conn->rollback();
        echo json_encode(["success" => false, "message" => "Failed to update status: " . $e->getMessage()]);
    }

    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
?>
