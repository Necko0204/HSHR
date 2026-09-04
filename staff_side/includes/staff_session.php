<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/security.php';

hshr_start_session('staff_session', '/staff_side/');
hshr_enforce_idle_timeout('employee_id');
hshr_csrf_token();

if (!empty($_SESSION['employee_id'])) {
    require_once __DIR__ . '/../../database_connection.php';
    $sessionConnection = createDatabaseConnection();
    $sessionStatement = $sessionConnection->prepare(
        "SELECT sa.username, sa.role
         FROM staff_accounts sa
         JOIN employees e ON e.id = sa.employee_id
         WHERE sa.employee_id = ? AND LOWER(sa.status) = 'active' AND e.status = 'Active'
         LIMIT 1"
    );
    $sessionStatement->bind_param('s', $_SESSION['employee_id']);
    $sessionStatement->execute();
    $sessionStaff = $sessionStatement->get_result()->fetch_assoc();
    $sessionStatement->close();
    $sessionConnection->close();

    $sessionRole = strtolower((string) ($sessionStaff['role'] ?? ''));
    if (!$sessionStaff || !in_array($sessionRole, ['staff', 'intern'], true)) {
        $_SESSION = [];
        session_regenerate_id(true);
    } else {
        $_SESSION['username'] = (string) $sessionStaff['username'];
        $_SESSION['role'] = $sessionRole;
    }
}
