<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_api.php';
require_once __DIR__ . '/../db_config.php';

function accountStatusResponse(int $status, bool $success, string $message, ?string $newStatus = null): never
{
    http_response_code($status);
    $payload = ['success' => $success, 'message' => $message];
    if ($newStatus !== null) $payload['newStatus'] = $newStatus;
    echo json_encode($payload);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    accountStatusResponse(405, false, 'Method not allowed.');
}

$accountId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$action = strtolower(trim((string) ($_POST['action'] ?? '')));
if (!$accountId || !in_array($action, ['deactivate', 'reactivate'], true)) {
    accountStatusResponse(422, false, 'Invalid account status request.');
}

$accountStatus = $action === 'deactivate' ? 'inactive' : 'active';
$employeeStatus = $action === 'deactivate' ? 'Inactive' : 'Active';

$conn->begin_transaction();
try {
    $lookup = $conn->prepare('SELECT employee_id FROM staff_accounts WHERE id = ? LIMIT 1 FOR UPDATE');
    $lookup->bind_param('i', $accountId);
    $lookup->execute();
    $employeeId = $lookup->get_result()->fetch_assoc()['employee_id'] ?? null;
    $lookup->close();
    if ($employeeId === null) {
        throw new OutOfBoundsException('Account not found.');
    }

    $account = $conn->prepare('UPDATE staff_accounts SET status = ? WHERE id = ?');
    $account->bind_param('si', $accountStatus, $accountId);
    $account->execute();
    $account->close();

    $employee = $conn->prepare('UPDATE employees SET status = ? WHERE id = ?');
    $employee->bind_param('ss', $employeeStatus, $employeeId);
    $employee->execute();
    $employee->close();
    $conn->commit();
} catch (OutOfBoundsException $error) {
    $conn->rollback();
    accountStatusResponse(404, false, $error->getMessage());
} catch (Throwable $error) {
    $conn->rollback();
    error_log('Staff account status update failed: ' . $error->getMessage());
    accountStatusResponse(500, false, 'Account status could not be updated.');
}

accountStatusResponse(200, true, 'Account status updated successfully.', $accountStatus);
