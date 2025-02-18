<?php
session_name('staff_session');
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_config.php';

// Ensure the user is logged in
if (!isset($_SESSION['employee_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to update your profile.']);
    exit();
}

$employee_id = $_SESSION['employee_id'];

// Check if the update form data is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $contact_number = isset($_POST['contact_number']) ? trim($_POST['contact_number']) : '';
    $home_address = isset($_POST['home_address']) ? trim($_POST['home_address']) : '';

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
        exit();
    }

    // Validate contact number (You can modify the regex as per the format you expect)
    if (!preg_match('/^[0-9]{10,15}$/', $contact_number)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid contact number format.']);
        exit();
    }

    // Prepare and execute the update query
    $stmt = $conn->prepare("UPDATE employees SET email1 = ?, mobilephone = ?, homeaddress = ? WHERE id = ?");
    $stmt->bind_param("sssi", $email, $contact_number, $home_address, $employee_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'There was an error updating your profile. Please try again.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
