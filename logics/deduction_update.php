<?php
require 'config.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $deduction_type = $_POST['deduction_type'];
    $amount = $_POST['amount'];
    $percentage = $_POST['percentage'];
    $max_cap = $_POST['max_cap'];
    $is_mandatory = $_POST['is_mandatory'];

    // Validate and sanitize input
    $id = intval($id);
    $name = htmlspecialchars(trim($name));
    $description = htmlspecialchars(trim($description));
    $deduction_type = htmlspecialchars(trim($deduction_type));
    $amount = floatval($amount);
    $percentage = floatval($percentage);
    $max_cap = floatval($max_cap);
    $is_mandatory = intval($is_mandatory);

    // Prepare update statement
    $sql = "UPDATE deductions 
            SET name = ?, 
                description = ?, 
                deduction_type = ?, 
                amount = ?, 
                percentage = ?, 
                max_cap = ?, 
                is_mandatory = ?, 
                updated_at = NOW()
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssddiii", $name, $description, $deduction_type, $amount, $percentage, $max_cap, $is_mandatory, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Deduction updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update deduction"]);
    }

    $stmt->close();
    $conn->close();
}
