<?php
session_name('staff_session');
session_start();
include 'db_config.php';

if (!isset($_SESSION['employee_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

$employee_id = $_SESSION['employee_id'];

// Fetch the background settings from the database
$stmt = $conn->prepare("SELECT background_choice, background_enabled FROM staff_background_settings WHERE employee_id = ?");
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $background_settings = $result->fetch_assoc();
    echo json_encode([
        'status' => 'success',
        'background_choice' => $background_settings['background_choice'],
        'background_enabled' => $background_settings['background_enabled']
    ]);
} else {
    // Default settings if no background settings exist for the user
    echo json_encode([
        'status' => 'success',
        'background_choice' => 'none',
        'background_enabled' => 0
    ]);
}

$stmt->close();
?>
