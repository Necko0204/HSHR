<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/staff_session.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, max-age=0');

if (empty($_SESSION['employee_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Authentication required.']);
    exit;
}

require_once __DIR__ . '/db_config.php';
$employeeId = (string) $_SESSION['employee_id'];
$statement = $conn->prepare('SELECT background_choice, background_enabled FROM staff_background_settings WHERE employee_id = ?');
$statement->bind_param('s', $employeeId);
$statement->execute();
$settings = $statement->get_result()->fetch_assoc();
$statement->close();

echo json_encode([
    'status' => 'success',
    'background_choice' => (string) ($settings['background_choice'] ?? 'none'),
    'background_enabled' => (int) ($settings['background_enabled'] ?? 0),
]);
