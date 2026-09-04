<?php
declare(strict_types=1);

require_once __DIR__ . '/../database_connection.php';

function getStaffData(?string $staffId): ?array
{
    static $cache = [];

    $staffId = trim((string) $staffId);
    if ($staffId === '') return null;
    if (array_key_exists($staffId, $cache)) return $cache[$staffId];

    global $conn;
    $ownsConnection = !isset($conn) || !($conn instanceof mysqli);
    $connection = $ownsConnection ? createDatabaseConnection() : $conn;

    $statement = $connection->prepare(
        'SELECT sa.id AS account_id, sa.employee_id, sa.username, sa.profile_picture, sa.role, sa.status AS account_status,
                e.*
         FROM staff_accounts sa
         JOIN employees e ON e.id = sa.employee_id
         WHERE sa.employee_id = ?
         LIMIT 1'
    );
    if (!$statement) {
        if ($ownsConnection) $connection->close();
        return $cache[$staffId] = null;
    }

    $statement->bind_param('s', $staffId);
    $statement->execute();
    $staff = $statement->get_result()->fetch_assoc() ?: null;
    $statement->close();
    if ($ownsConnection) $connection->close();

    return $cache[$staffId] = $staff;
}

$staffId = isset($_SESSION['employee_id']) ? (string) $_SESSION['employee_id'] : null;
$staffData = getStaffData($staffId);
