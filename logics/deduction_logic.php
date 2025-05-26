<?php
session_start();
require_once 'db_config.php'; // Adjust path based on your structure

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $response = ['status' => 'error', 'message' => 'Something went wrong!'];

    // Retrieve form data
    $name = trim($_POST['deductionName']);
    $description = trim($_POST['deductionDescription']);
    $deduction_type = $_POST['deductionType'];
    $amount = isset($_POST['deductionAmount']) ? floatval($_POST['deductionAmount']) : null;
    $percentage = isset($_POST['deductionPercentage']) ? floatval($_POST['deductionPercentage']) : null;
    $max_cap = isset($_POST['deductionMaxCap']) ? floatval($_POST['deductionMaxCap']) : null;
    $is_mandatory = $_POST['isMandatory'];

    // Generate ID (HSHI-DEDYYYY000001)
    $year = date("Y");
    $sql = "SELECT id FROM deductions ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastId = intval(substr($row['id'], -6));
        $newId = "HSHI-DED" . $year . str_pad($lastId + 1, 6, '0', STR_PAD_LEFT);
    } else {
        $newId = "HSHI-DED" . $year . "000001";
    }

    // Validate required fields
    if (empty($name) || empty($deduction_type) || ($deduction_type == "fixed" && empty($amount)) || ($deduction_type == "percentage" && empty($percentage))) {
        $response['message'] = 'Please fill in all required fields.';
        echo json_encode($response);
        exit();
    }

    // Insert deduction into database
    $stmt = $conn->prepare("INSERT INTO deductions (id, name, description, deduction_type, amount, percentage, max_cap, is_mandatory, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssssdddi", $newId, $name, $description, $deduction_type, $amount, $percentage, $max_cap, $is_mandatory);

    if ($stmt->execute()) {
        $response = ['status' => 'success', 'message' => 'Deduction added successfully!'];
    } else {
        $response['message'] = 'Error adding deduction. Please try again.';
    }

    $stmt->close();
    $conn->close();

    echo json_encode($response);
    exit();
}
?>
