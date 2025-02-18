<?php
session_name('staff_session');
session_start();
include 'db_config.php';

if (!isset($_SESSION['employee_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

$employee_id = $_SESSION['employee_id'];
$background_choice = $_POST['background_choice'];
$background_enabled = $_POST['background_enabled'];

// Mapping background names to their IDs
$background_choice_id = null;

switch ($background_choice) {
    case 'particles':
        $background_choice_id = 1; // ID for 'particles'
        break;
    case 'clouds':
        $background_choice_id = 2; // ID for 'clouds'
        break;
    case 'stars':
        $background_choice_id = 3; // ID for 'stars'
        break;
    case 'none':
    default:
        $background_choice_id = 0; // ID for 'none' (no background)
        break;
}

// Check if background settings already exist for the employee
$stmt = $conn->prepare("SELECT * FROM staff_background_settings WHERE employee_id = ?");
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update the existing settings
    $stmt = $conn->prepare("UPDATE staff_background_settings SET background_choice_id = ?, background_enabled = ? WHERE employee_id = ?");
    $stmt->bind_param("iii", $background_choice_id, $background_enabled, $employee_id);
} else {
    // Insert new settings
    $stmt = $conn->prepare("INSERT INTO staff_background_settings (employee_id, background_choice_id, background_enabled) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $employee_id, $background_choice_id, $background_enabled);
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Background settings updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update background settings']);
}

$stmt->close();
?>
