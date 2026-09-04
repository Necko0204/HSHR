<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/security.php';
hshr_security_headers();
header('Content-Type: text/plain; charset=utf-8');
header('Cache-Control: no-store, max-age=0');

try {
    require_once __DIR__ . '/database_connection.php';
    $connection = createDatabaseConnection();
    $connection->query('SELECT 1');
    $connection->close();
    echo 'ok';
} catch (Throwable $error) {
    error_log('Health check failed: ' . $error->getMessage());
    http_response_code(503);
    echo 'unavailable';
}
