<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/attendance_api.php';
require_once __DIR__ . '/db_config.php';

$conn->begin_transaction();

try {
    $attendance = $conn->prepare(
        'SELECT time_in, time_out, break_in, break_out, break_duration
         FROM attendance
         WHERE employee_id = ? AND date = CURDATE()
         LIMIT 1 FOR UPDATE'
    );
    $attendance->bind_param('s', $attendanceEmployeeId);
    $attendance->execute();
    $row = $attendance->get_result()->fetch_assoc();
    $attendance->close();

    if (!$row || empty($row['time_in'])) {
        $conn->rollback();
        attendance_respond(false, 'No clock-in record was found for today.', 409);
    }
    if ($row['time_out'] === '23:59:59') {
        $conn->rollback();
        attendance_respond(false, 'An automatic timeout has already been applied.', 409);
    }
    if (!empty($row['time_out'])) {
        $conn->rollback();
        attendance_respond(false, 'You have already clocked out today.', 409);
    }
    if (!empty($row['break_in']) && empty($row['break_out'])) {
        $conn->rollback();
        attendance_respond(false, 'End your active break before clocking out.', 409);
    }

    $clockOutTime = date('H:i:s');
    $today = date('Y-m-d');
    $clockInTimestamp = strtotime($today . ' ' . $row['time_in']);
    $clockOutTimestamp = strtotime($today . ' ' . $clockOutTime);
    $breakSeconds = !empty($row['break_duration'])
        ? max(0, (int) strtotime('1970-01-01 ' . $row['break_duration'] . ' UTC'))
        : 0;
    $workedSeconds = max(0, $clockOutTimestamp - $clockInTimestamp - $breakSeconds);
    $totalHours = sprintf(
        '%02d:%02d:%02d',
        intdiv($workedSeconds, 3600),
        intdiv($workedSeconds % 3600, 60),
        $workedSeconds % 60
    );

    $dayOfWeek = date('l');
    $schedule = $conn->prepare(
        'SELECT id, required_hours
         FROM work_schedules
         WHERE employee_id = ? AND day_of_week = ?
         LIMIT 1'
    );
    $schedule->bind_param('ss', $attendanceEmployeeId, $dayOfWeek);
    $schedule->execute();
    $scheduleRow = $schedule->get_result()->fetch_assoc();
    $schedule->close();

    $workScheduleId = isset($scheduleRow['id']) ? (int) $scheduleRow['id'] : null;
    $requiredHours = isset($scheduleRow['required_hours']) ? (float) $scheduleRow['required_hours'] : 8.0;
    $workedHours = $workedSeconds / 3600;

    if ($workedHours > $requiredHours) {
        $logStatus = 'overtime';
        $differenceHours = $workedHours - $requiredHours;
    } elseif ($workedHours < $requiredHours) {
        $logStatus = 'undertime';
        $differenceHours = $requiredHours - $workedHours;
    } else {
        $logStatus = 'on time';
        $differenceHours = 0.0;
    }
    $differenceHours = round($differenceHours, 2);

    $update = $conn->prepare(
        "UPDATE attendance
         SET time_out = ?, total_hours = ?, status = 'Present'
         WHERE employee_id = ? AND date = CURDATE() AND time_out IS NULL"
    );
    $update->bind_param('sss', $clockOutTime, $totalHours, $attendanceEmployeeId);
    $update->execute();

    if ($update->affected_rows !== 1) {
        $update->close();
        throw new RuntimeException('Clock-out row was not updated.');
    }
    $update->close();

    $log = $conn->prepare(
        'INSERT INTO overtime_undertime_logs (employee_id, work_schedule_id, date, status, hours)
         VALUES (?, ?, CURDATE(), ?, ?)
         ON DUPLICATE KEY UPDATE
             work_schedule_id = VALUES(work_schedule_id),
             status = VALUES(status),
             hours = VALUES(hours)'
    );
    $log->bind_param('sisd', $attendanceEmployeeId, $workScheduleId, $logStatus, $differenceHours);
    $log->execute();
    $log->close();

    $conn->commit();
    attendance_respond(true, 'Clock-out successful. Your worked hours have been updated.', 200, [
        'total_hours' => $totalHours,
        'work_status' => $logStatus,
    ]);
} catch (Throwable $error) {
    $conn->rollback();
    error_log('Clock-out failed: ' . $error->getMessage());
    attendance_respond(false, 'Clock-out could not be recorded. Please try again.', 500);
}
