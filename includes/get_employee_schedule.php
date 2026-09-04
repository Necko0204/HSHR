<?php
declare(strict_types=1);

require_once __DIR__ . '/admin_api.php';
require_once __DIR__ . '/db_config.php';

$employeeId = trim((string) ($_GET['employee_id'] ?? ''));
if ($employeeId === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Employee ID is required.']);
    exit;
}

$employeeStatement = $conn->prepare(
    "SELECT id, firstname, lastname, employment_type
     FROM employees
     WHERE id = ? AND status = 'Active'
     LIMIT 1"
);
$employeeStatement->bind_param('s', $employeeId);
$employeeStatement->execute();
$employee = $employeeStatement->get_result()->fetch_assoc();
$employeeStatement->close();

if (!$employee) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Active employee not found.']);
    exit;
}

$scheduleStatement = $conn->prepare(
    "SELECT day_of_week, required_hours,
            TIME_FORMAT(start_time, '%H:%i') AS start_time,
            TIME_FORMAT(end_time, '%H:%i') AS end_time,
            TIME_FORMAT(break_start, '%H:%i') AS break_start,
            TIME_FORMAT(break_end, '%H:%i') AS break_end,
            break_minutes, preset_name, timezone
     FROM work_schedules
     WHERE employee_id = ?
     ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')"
);
$scheduleStatement->bind_param('s', $employeeId);
$scheduleStatement->execute();
$result = $scheduleStatement->get_result();
$days = [];
$weeklyHours = 0.0;
$presetName = null;

while ($row = $result->fetch_assoc()) {
    $hours = (float) $row['required_hours'];
    $weeklyHours += $hours;
    $presetName ??= $row['preset_name'];
    $days[] = [
        'day' => $row['day_of_week'],
        'start_time' => $row['start_time'],
        'end_time' => $row['end_time'],
        'break_start' => $row['break_start'],
        'break_end' => $row['break_end'],
        'break_minutes' => (int) $row['break_minutes'],
        'required_hours' => $hours,
    ];
}
$scheduleStatement->close();

echo json_encode([
    'success' => true,
    'employee' => [
        'id' => $employee['id'],
        'name' => trim($employee['firstname'] . ' ' . $employee['lastname']),
        'employment_type' => $employee['employment_type'],
    ],
    'schedule' => [
        'days' => $days,
        'day_count' => count($days),
        'weekly_hours' => round($weeklyHours, 2),
        'preset_name' => $presetName,
        'timezone' => 'Asia/Manila',
    ],
]);
