<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_api.php';
require_once __DIR__ . '/../db_config.php';

function leaveStatusResponse(int $status, bool $success, string $message): never
{
    http_response_code($status);
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') leaveStatusResponse(405, false, 'Method not allowed.');
$leaveId = filter_input(INPUT_POST, 'leave_id', FILTER_VALIDATE_INT);
$action = strtolower(trim((string) ($_POST['action'] ?? '')));
if (!$leaveId || !in_array($action, ['approve', 'reject'], true)) {
    leaveStatusResponse(422, false, 'Invalid leave status request.');
}

$status = $action === 'approve' ? 'Approved' : 'Rejected';
$statement = $conn->prepare("UPDATE leave_requests SET status = ? WHERE leave_id = ? AND status = 'Pending'");
$statement->bind_param('si', $status, $leaveId);
$statement->execute();
$updated = $statement->affected_rows;
$statement->close();
if ($updated !== 1) leaveStatusResponse(409, false, 'The leave request is missing or has already been reviewed.');

leaveStatusResponse(200, true, 'Leave request updated successfully.');
