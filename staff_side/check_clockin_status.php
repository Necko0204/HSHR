<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/staff_session.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, max-age=0');

if (empty($_SESSION['employee_id'])) {
    http_response_code(401);
    echo json_encode(['already_clocked_in' => false, 'message' => 'Authentication required.']);
    exit;
}

require_once __DIR__ . '/db_config.php';
$employeeId = (string) $_SESSION['employee_id'];
$statement = $conn->prepare(
    'SELECT time_out FROM attendance WHERE employee_id = ? AND date = CURDATE() LIMIT 1'
);
$statement->bind_param('s', $employeeId);
$statement->execute();
$row = $statement->get_result()->fetch_assoc();
$statement->close();

echo json_encode([
    'already_clocked_in' => $row !== null,
    'already_clocked_out' => $row !== null && !empty($row['time_out']),
]);
