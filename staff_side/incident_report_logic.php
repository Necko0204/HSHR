<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/staff_session.php';

if (empty($_SESSION['employee_id']) || !in_array(strtolower((string) ($_SESSION['role'] ?? '')), ['staff', 'intern'], true)) {
    header('Location: index.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: incident_report.php');
    exit;
}
if (!hshr_validate_csrf()) {
    $_SESSION['error_message'] = 'Refresh the incident report page and try again.';
    header('Location: incident_report.php');
    exit;
}

require_once __DIR__ . '/db_config.php';
$employeeId = (string) $_SESSION['employee_id'];
$fields = [
    'incident_date', 'incident_time', 'location', 'incident_type', 'persons_involved',
    'description', 'action_taken', 'cause', 'impact', 'severity', 'recommendations',
    'disciplinary_actions', 'additional_support', 'follow_up', 'conclusion'
];
$values = [];
foreach ($fields as $field) $values[$field] = trim((string) ($_POST[$field] ?? ''));
$customType = $values['incident_type'] === 'Others' ? trim((string) ($_POST['custom_incident_type'] ?? '')) : null;

if ($values['incident_date'] === '' || $values['incident_time'] === '' || $values['location'] === '' || $values['incident_type'] === '' || $values['description'] === '') {
    $_SESSION['error_message'] = 'Please complete the required incident details.';
    header('Location: incident_report.php');
    exit;
}
foreach ($values as $value) {
    if (mb_strlen($value) > 5000) {
        $_SESSION['error_message'] = 'An incident report field is too long.';
        header('Location: incident_report.php');
        exit;
    }
}

$statement = $conn->prepare(
    'INSERT INTO incident_reports
     (employee_id, incident_date, incident_time, location, incident_type, custom_incident_type,
      persons_involved, description, action_taken, cause, impact, severity, recommendations,
      disciplinary_actions, additional_support, follow_up, conclusion)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
);
$statement->bind_param(
    'sssssssssssssssss',
    $employeeId, $values['incident_date'], $values['incident_time'], $values['location'],
    $values['incident_type'], $customType, $values['persons_involved'], $values['description'],
    $values['action_taken'], $values['cause'], $values['impact'], $values['severity'],
    $values['recommendations'], $values['disciplinary_actions'], $values['additional_support'],
    $values['follow_up'], $values['conclusion']
);
$saved = $statement->execute();
$statement->close();
$_SESSION[$saved ? 'success_message' : 'error_message'] = $saved
    ? 'Incident report submitted successfully.'
    : 'The incident report could not be submitted. Please try again.';
header('Location: incident_report.php');
exit;
