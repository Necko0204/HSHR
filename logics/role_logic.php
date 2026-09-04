<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_api.php';
require_once __DIR__ . '/../db_config.php';

function roleCreateResponse(int $status, bool $success, string $message, array $extra = []): never
{
    http_response_code($status);
    echo json_encode(['success' => $success, 'message' => $message] + $extra);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') roleCreateResponse(405, false, 'Method not allowed.');
$roleName = trim((string) ($_POST['roleName'] ?? ''));
$description = trim((string) ($_POST['roleDescription'] ?? ''));
if ($roleName === '' || mb_strlen($roleName) > 100 || mb_strlen($description) > 500) {
    roleCreateResponse(422, false, 'Enter a role name and a description within the allowed length.');
}

$locked = false;
$conn->begin_transaction();
try {
    $lockResult = $conn->query("SELECT GET_LOCK('hshr_role_sequence', 5) AS acquired");
    $locked = (int) ($lockResult->fetch_assoc()['acquired'] ?? 0) === 1;
    if (!$locked) throw new RuntimeException('Role sequence is busy.');

    $prefix = 'HSHI-ROLE';
    $last = $conn->prepare('SELECT role_id FROM roles WHERE role_id LIKE CONCAT(?, \'%\') ORDER BY role_id DESC LIMIT 1 FOR UPDATE');
    $last->bind_param('s', $prefix);
    $last->execute();
    $lastId = $last->get_result()->fetch_assoc()['role_id'] ?? null;
    $last->close();
    $number = $lastId ? ((int) substr((string) $lastId, -8) + 1) : 1;
    $roleId = $prefix . str_pad((string) $number, 8, '0', STR_PAD_LEFT);

    $insert = $conn->prepare('INSERT INTO roles (role_id, role_name, description) VALUES (?, ?, ?)');
    $insert->bind_param('sss', $roleId, $roleName, $description);
    $insert->execute();
    $insert->close();
    $conn->commit();
    try { $conn->query("SELECT RELEASE_LOCK('hshr_role_sequence')"); } catch (Throwable $ignored) {}
    roleCreateResponse(201, true, 'New role added successfully.', ['role_id' => $roleId]);
} catch (Throwable $error) {
    $conn->rollback();
    if ($locked) try { $conn->query("SELECT RELEASE_LOCK('hshr_role_sequence')"); } catch (Throwable $ignored) {}
    error_log('Role creation failed: ' . $error->getMessage());
    $status = $error instanceof mysqli_sql_exception && (int) $error->getCode() === 1062 ? 409 : 500;
    roleCreateResponse($status, false, $status === 409 ? 'A role with that name already exists.' : 'Role could not be created.');
}
