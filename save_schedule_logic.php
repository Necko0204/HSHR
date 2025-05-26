<?php
// save_schedule_logic.php
ini_set('display_errors', 0);
ini_set('log_errors',     1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require 'db_config.php';  // make sure this defines $conn as your mysqli handle

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // 1) Read the raw JSON
    $raw = file_get_contents('php://input');
    $obj = json_decode($raw, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON: ' . json_last_error_msg());
    }

    $emp = $obj['employee_id'] ?? '';
    $hrs = $obj['hours']       ?? [];

    if ($emp === '' || !is_array($hrs)) {
        throw new Exception('Malformed data');
    }

    // 2) Start transaction
    $conn->begin_transaction();

    // 3) Delete any old schedule for this employee
    $del = $conn->prepare("
        DELETE FROM work_schedules
         WHERE employee_id = ?
    ");
    $del->bind_param("s", $emp);
    $del->execute();
    if ($del->errno) {
        throw new Exception("Delete failed: " . $del->error);
    }
    $del->close();

    // 4) Insert new rows
    $ins = $conn->prepare("
        INSERT INTO work_schedules
           (employee_id, day_of_week, required_hours)
        VALUES
           (?, ?, ?)
    ");

    // Only days 1–5 (Mon–Fri)
    for ($d = 1; $d <= 5; $d++) {
        $h = floatval($hrs[$d] ?? 0);
        if ($h > 0) {
            $ins->bind_param("sid", $emp, $d, $h);
            $ins->execute();
            if ($ins->errno) {
                throw new Exception("Insert failed for day $d: " . $ins->error);
            }
        }
    }

    $ins->close();
    $conn->commit();

    // 5) Return success
    echo json_encode([
        'success' => true,
        'message' => 'Schedule saved and ' . $conn->affected_rows . ' rows written.'
    ]);
    exit;
}
catch (Exception $e) {
    if ($conn->errno) {
        $conn->rollback();
    }
    // Log the error server-side and return a safe JSON payload
    error_log("Schedule save error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}
