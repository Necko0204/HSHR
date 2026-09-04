<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/attendance_api.php';
require_once __DIR__ . '/db_config.php';

$conn->begin_transaction();

try {
    $attendance = $conn->prepare(
        'SELECT time_in, time_out, break_in, break_out
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
        attendance_respond(false, 'Clock in before ending a break.', 409);
    }
    if (!empty($row['time_out'])) {
        $conn->rollback();
        attendance_respond(false, 'A break cannot be ended after clocking out.', 409);
    }
    if (empty($row['break_in'])) {
        $conn->rollback();
        attendance_respond(false, 'No active break was found. Start your break first.', 409);
    }
    if (!empty($row['break_out'])) {
        $conn->rollback();
        attendance_respond(false, 'You have already ended your break today.', 409);
    }

    $update = $conn->prepare(
        'UPDATE attendance
         SET break_out = CURTIME(),
             break_duration = SEC_TO_TIME(
                 GREATEST(TIME_TO_SEC(TIMEDIFF(CURTIME(), break_in)), 0)
             )
         WHERE employee_id = ? AND date = CURDATE() AND break_out IS NULL'
    );
    $update->bind_param('s', $attendanceEmployeeId);
    $update->execute();

    if ($update->affected_rows !== 1) {
        $update->close();
        throw new RuntimeException('Break-out row was not updated.');
    }
    $update->close();
    $conn->commit();

    attendance_respond(true, 'Break ended successfully.');
} catch (Throwable $error) {
    $conn->rollback();
    error_log('Break-out failed: ' . $error->getMessage());
    attendance_respond(false, 'Break-out could not be recorded. Please try again.', 500);
}
