<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/staff_session.php';
header('Content-Type: application/json; charset=utf-8');

function leaveResponse(int $status, string $type, string $title, string $message): never
{
    http_response_code($status);
    echo json_encode(['status' => $type, 'title' => $title, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') leaveResponse(405, 'error', 'Invalid request', 'Method not allowed.');
if (empty($_SESSION['employee_id'])) leaveResponse(401, 'error', 'Unauthorized', 'Authentication required.');
if (!hshr_validate_csrf()) leaveResponse(403, 'error', 'Session expired', 'Refresh the page and try again.');

require_once __DIR__ . '/db_config.php';
$employeeId = (string) $_SESSION['employee_id'];
$leaveTypeId = filter_input(INPUT_POST, 'leave_type_id', FILTER_VALIDATE_INT);
$leaveDates = trim((string) ($_POST['leave_dates'] ?? ''));
if (!$leaveTypeId || $leaveDates === '') leaveResponse(422, 'error', 'Missing details', 'Select a leave type and valid dates.');

$dateParts = str_contains($leaveDates, ' to ') ? explode(' to ', $leaveDates, 2) : [$leaveDates, $leaveDates];
$start = DateTimeImmutable::createFromFormat('!Y-m-d', trim($dateParts[0]));
$end = DateTimeImmutable::createFromFormat('!Y-m-d', trim($dateParts[1]));
$startErrors = DateTimeImmutable::getLastErrors();
if (!$start || !$end || ($startErrors !== false && ($startErrors['warning_count'] || $startErrors['error_count'])) || $end < $start) {
    leaveResponse(422, 'error', 'Invalid dates', 'Enter a valid leave period with the end date on or after the start date.');
}
$totalDays = $start->diff($end)->days + 1;
if ($totalDays > 365) leaveResponse(422, 'error', 'Invalid period', 'A leave request cannot exceed 365 days.');

$startDate = $start->format('Y-m-d');
$endDate = $end->format('Y-m-d');
$conn->begin_transaction();
try {
    $type = $conn->prepare('SELECT max_days FROM leave_types WHERE leave_type_id = ? LIMIT 1 FOR UPDATE');
    $type->bind_param('i', $leaveTypeId);
    $type->execute();
    $leaveType = $type->get_result()->fetch_assoc();
    $type->close();
    if (!$leaveType) {
        $conn->rollback();
        leaveResponse(404, 'error', 'Leave type unavailable', 'The selected leave type no longer exists.');
    }

    $committed = $conn->prepare(
        "SELECT COALESCE(SUM(total_days), 0) AS committed_days FROM leave_requests
         WHERE employee_id = ? AND leave_type_id = ? AND status IN ('Approved', 'Pending')"
    );
    $committed->bind_param('si', $employeeId, $leaveTypeId);
    $committed->execute();
    $committedDays = (int) ($committed->get_result()->fetch_assoc()['committed_days'] ?? 0);
    $committed->close();
    $remaining = max(0, (int) $leaveType['max_days'] - $committedDays);
    if ($totalDays > $remaining) {
        $conn->rollback();
        leaveResponse(422, 'warning', 'Insufficient balance', "You have {$remaining} available day" . ($remaining === 1 ? '' : 's') . ' for this leave type.');
    }

    $overlap = $conn->prepare(
        "SELECT 1 FROM leave_requests
         WHERE employee_id = ? AND status IN ('Pending', 'Approved')
           AND leave_start_date <= ? AND leave_end_date >= ? LIMIT 1 FOR UPDATE"
    );
    $overlap->bind_param('sss', $employeeId, $endDate, $startDate);
    $overlap->execute();
    $hasOverlap = (bool) $overlap->get_result()->fetch_row();
    $overlap->close();
    if ($hasOverlap) {
        $conn->rollback();
        leaveResponse(409, 'warning', 'Overlapping request', 'You already have a pending or approved leave request in this date range.');
    }

    $statement = $conn->prepare(
        "INSERT INTO leave_requests
         (employee_id, leave_type_id, leave_start_date, leave_end_date, total_days, status, request_date)
         VALUES (?, ?, ?, ?, ?, 'Pending', NOW())"
    );
    $statement->bind_param('sissi', $employeeId, $leaveTypeId, $startDate, $endDate, $totalDays);
    $statement->execute();
    $statement->close();
    $conn->commit();
} catch (Throwable $error) {
    $conn->rollback();
    error_log('Leave submission failed: ' . $error->getMessage());
    leaveResponse(500, 'error', 'Request failed', 'Your leave request could not be saved. Please try again.');
}

leaveResponse(201, 'success', 'Request submitted', 'Your leave request has been recorded for review.');
