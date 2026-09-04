<?php
declare(strict_types=1);

require_once __DIR__ . '/security.php';

hshr_start_session('admin_session', '/');
hshr_enforce_idle_timeout('admin_id');
hshr_csrf_token();

if (!empty($_SESSION['admin_id'])) {
    require_once __DIR__ . '/../database_connection.php';
    $sessionConnection = createDatabaseConnection();
    $sessionStatement = $sessionConnection->prepare("SELECT username, position FROM admin WHERE id = ? AND position = 'Administrator' LIMIT 1");
    $sessionStatement->bind_param('s', $_SESSION['admin_id']);
    $sessionStatement->execute();
    $sessionAdmin = $sessionStatement->get_result()->fetch_assoc();
    $sessionStatement->close();
    $sessionConnection->close();

    if (!$sessionAdmin) {
        $_SESSION = [];
        session_regenerate_id(true);
    } else {
        $_SESSION['admin_username'] = (string) $sessionAdmin['username'];
        $_SESSION['position'] = 'Administrator';
    }
}
