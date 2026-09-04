<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/admin_api.php';
require_once __DIR__ . '/db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'This endpoint requires a POST request.']);
    exit;
}

function scheduleMinutes(string $time): ?int
{
    if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $time)) {
        return null;
    }
    [$hours, $minutes] = array_map('intval', explode(':', $time));
    return ($hours * 60) + $minutes;
}

$payload = json_decode((string) file_get_contents('php://input'), true);
if (!is_array($payload)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid schedule request.']);
    exit;
}

$employeeId = trim((string) ($payload['employee_id'] ?? ''));
$days = $payload['days'] ?? [];
$clearSchedule = filter_var($payload['clear'] ?? false, FILTER_VALIDATE_BOOL);
$presetName = substr(trim((string) ($payload['preset_name'] ?? 'Custom weekly schedule')), 0, 60);

if ($employeeId === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Choose an employee before saving.']);
    exit;
}
if (!$clearSchedule && (!is_array($days) || count($days) < 1 || count($days) > 7)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Select at least one and at most seven working days.']);
    exit;
}

$employeeStatement = $conn->prepare("SELECT 1 FROM employees WHERE id = ? AND status = 'Active' LIMIT 1");
$employeeStatement->bind_param('s', $employeeId);
$employeeStatement->execute();
$employeeExists = (bool) $employeeStatement->get_result()->fetch_row();
$employeeStatement->close();
if (!$employeeExists) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Active employee not found.']);
    exit;
}

$allowedDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$normalizedDays = [];
$seenDays = [];
$weeklyHours = 0.0;

if (!$clearSchedule) {
    foreach ($days as $index => $dayInput) {
        if (!is_array($dayInput)) {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'Invalid day entry at position ' . ($index + 1) . '.']);
            exit;
        }

        $day = (string) ($dayInput['day'] ?? '');
        $startTime = (string) ($dayInput['start_time'] ?? '');
        $endTime = (string) ($dayInput['end_time'] ?? '');
        $breakStart = trim((string) ($dayInput['break_start'] ?? ''));
        $breakEnd = trim((string) ($dayInput['break_end'] ?? ''));

        if (!in_array($day, $allowedDays, true) || isset($seenDays[$day])) {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'Each selected weekday must appear exactly once.']);
            exit;
        }
        $seenDays[$day] = true;

        $startMinutes = scheduleMinutes($startTime);
        $endMinutes = scheduleMinutes($endTime);
        if ($startMinutes === null || $endMinutes === null || $endMinutes <= $startMinutes) {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => $day . ': end time must be later than start time.']);
            exit;
        }

        $shiftMinutes = $endMinutes - $startMinutes;
        if ($shiftMinutes > 16 * 60) {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => $day . ': a shift cannot exceed 16 hours.']);
            exit;
        }

        $breakMinutes = 0;
        if ($breakStart !== '' || $breakEnd !== '') {
            $breakStartMinutes = scheduleMinutes($breakStart);
            $breakEndMinutes = scheduleMinutes($breakEnd);
            if (
                $breakStartMinutes === null ||
                $breakEndMinutes === null ||
                $breakEndMinutes <= $breakStartMinutes ||
                $breakStartMinutes < $startMinutes ||
                $breakEndMinutes > $endMinutes
            ) {
                http_response_code(422);
                echo json_encode(['success' => false, 'message' => $day . ': break time must fall completely inside the shift.']);
                exit;
            }
            $breakMinutes = $breakEndMinutes - $breakStartMinutes;
        } else {
            $breakStart = null;
            $breakEnd = null;
        }

        $paidMinutes = $shiftMinutes - $breakMinutes;
        if ($paidMinutes <= 0) {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => $day . ': paid working time must be greater than zero.']);
            exit;
        }

        $requiredHours = round($paidMinutes / 60, 2);
        $weeklyHours += $requiredHours;
        $normalizedDays[] = [
            'day' => $day,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'break_start' => $breakStart,
            'break_end' => $breakEnd,
            'break_minutes' => $breakMinutes,
            'required_hours' => $requiredHours,
        ];
    }
}

$conn->begin_transaction();
try {
    $delete = $conn->prepare('DELETE FROM work_schedules WHERE employee_id = ?');
    $delete->bind_param('s', $employeeId);
    $delete->execute();
    $delete->close();

    if (!$clearSchedule) {
        $insert = $conn->prepare(
            "INSERT INTO work_schedules
                (employee_id, day_of_week, required_hours, start_time, end_time,
                 break_start, break_end, break_minutes, preset_name, timezone)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Asia/Manila')"
        );

        foreach ($normalizedDays as $day) {
            $insert->bind_param(
                'ssdssssis',
                $employeeId,
                $day['day'],
                $day['required_hours'],
                $day['start_time'],
                $day['end_time'],
                $day['break_start'],
                $day['break_end'],
                $day['break_minutes'],
                $presetName
            );
            $insert->execute();
        }
        $insert->close();
    }

    $conn->commit();
    echo json_encode([
        'success' => true,
        'message' => $clearSchedule ? 'Weekly schedule cleared.' : 'Weekly schedule saved successfully.',
        'day_count' => count($normalizedDays),
        'weekly_hours' => round($weeklyHours, 2),
        'timezone' => 'Asia/Manila',
    ]);
} catch (Throwable $error) {
    $conn->rollback();
    error_log('Schedule save failed: ' . $error->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'The schedule could not be saved. Please try again.']);
}
