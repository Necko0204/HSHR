<?php
session_name('staff_session');
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'staff_helper.php';
include 'db_config.php';

// Assign session values
$employee_id = $_SESSION['employee_id']; // This is the staff member reporting the incident

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id  = $_POST['employee_id'];
    $incident_date = $_POST['incident_date'];
    $incident_time = $_POST['incident_time'];
    $location = $_POST['location'];
    $incident_type = $_POST['incident_type'];
    $custom_incident_type = ($_POST['incident_type'] == 'Others') ? $_POST['custom_incident_type'] : NULL;
    $persons_involved = $_POST['persons_involved'];
    $description = $_POST['description'];
    $action_taken = $_POST['action_taken'];
    $cause = $_POST['cause'];
    $impact = $_POST['impact'];
    $severity = $_POST['severity'];
    $recommendations = $_POST['recommendations'];
    $disciplinary_actions = $_POST['disciplinary_actions'];
    $additional_support = $_POST['additional_support'];
    $follow_up = $_POST['follow_up'];
    $conclusion = $_POST['conclusion'];

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO incident_reports (employee_id,incident_date, incident_time, location, incident_type, custom_incident_type, persons_involved, description, action_taken, cause, impact, severity, recommendations, disciplinary_actions, additional_support, follow_up,conclusion) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssssssss",  $employee_id , $incident_date, $incident_time, $location, $incident_type, $custom_incident_type, $persons_involved, $description, $action_taken, $cause, $impact, $severity, $recommendations, $disciplinary_actions, $additional_support, $follow_up,$conclusion);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Incident report submitted successfully!";
    } else {
        $_SESSION['error_message'] = "Error submitting report. Please try again.";
    }

    $stmt->close();
    header("Location: incident_report.php"); // Redirect back to the form
    exit();
}
?>