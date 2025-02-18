<?php
session_name('staff_session');
session_start();
include 'db_config.php';

if (!isset($_SESSION['employee_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit();
}

$employee_id = $_SESSION['employee_id'];
$username = $_POST['username'];
$password = $_POST['password'];

// Validate the input
if (empty($username) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Username and password are required.']);
    exit();
}

// Hash the new password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Update the username and password in the database
$stmt = $conn->prepare("UPDATE staff_accounts SET username = ?, password = ? WHERE employee_id = ?");
$stmt->bind_param("ssi", $username, $hashed_password, $employee_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update profile. Please try again.']);
}

$stmt->close();
$conn->close();
?>
