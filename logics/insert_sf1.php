<?php
ini_set('display_errors', '0');
require_once __DIR__ . '/../includes/admin_api.php';
include 'db_config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $lrn = $_POST['lrn'] ?? null;
    $lastName = $_POST['lastName'] ?? null;
    $firstName = $_POST['firstName'] ?? null;
    $middleName = $_POST['middleName'] ?? null;
    $sex = $_POST['sex'] ?? null;
    $birthdate = $_POST['birthdate'] ?? null;
    $age = $_POST['age'] ?? null;
    $motherTongue = $_POST['motherTongue'] ?? null;
    $ip = $_POST['IP'] ?? null;
    $religion = $_POST['religion'] ?? null;
    $barangay = $_POST['barangay'] ?? null;
    $municipality = $_POST['municipality'] ?? null;
    $province = $_POST['province'] ?? null;
    $fatherName = $_POST['fatherName'] ?? null;
    $motherMaidenName = $_POST['motherMaidenName'] ?? null;
    $guardianName = $_POST['guardianName'] ?? null;
    $guardianRelationship = $_POST['guardianRelationship'] ?? null;
    $guardianContact = $_POST['guardianContact'] ?? null;
    $learningModality = $_POST['learningModality'] ?? null;
    $remarks = $_POST['remarks'] ?? null;
    $section = $_POST['section'] ?? null;

    // Validate required fields
    if (!$lrn || !$lastName || !$firstName || !$sex || !$birthdate || !$age) {
        echo json_encode(["status" => "error", "message" => "Missing required fields."]);
        exit();
    }

    try {
        if (!$conn) {
            throw new Exception("Database connection not initialized.");
        }

        // Prepare one placeholder for each of the 21 submitted SF1 columns.
        $stmt = $conn->prepare("INSERT INTO sf1 (LRN, Last_Name, First_Name, Middle_Name, Sex, Birthdate, Age_As_Of_October_31, Mother_Tongue, IP, Religion, Barangay, Municipality, Province, Father_Name, Mother_Maiden_Name, Guardian_Name, Guardian_Relationship, Guardian_Contact, Learning_Modality, Remarks,section) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Bind 21 parameters: 6 strings, 1 integer, then 14 strings.
        $stmt->bind_param(
            "ssssssissssssssssssss",
            $lrn,
            $lastName,
            $firstName,
            $middleName,
            $sex,
            $birthdate,
            $age,
            $motherTongue,
            $ip,
            $religion,
            $barangay,
            $municipality,
            $province,
            $fatherName,
            $motherMaidenName,
            $guardianName,
            $guardianRelationship,
            $guardianContact,
            $learningModality,
            $remarks,
            $section
        );

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "SF1 Entry Added Successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to add entry."]);
        }

        $stmt->close();
    } catch (Exception $e) {
        error_log('SF1 entry creation failed: ' . $e->getMessage());
        echo json_encode(["status" => "error", "message" => "The SF1 entry could not be created."]);
    }

    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
