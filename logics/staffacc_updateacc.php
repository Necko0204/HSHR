<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_api.php';
require_once __DIR__ . '/../db_config.php';

function accountUpdateResponse(int $status, bool $success, string $message): never
{
    http_response_code($status);
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    accountUpdateResponse(405, false, 'Method not allowed.');
}

$accountId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$username = trim((string) ($_POST['username'] ?? ''));
$role = strtolower(trim((string) ($_POST['role'] ?? '')));

if (!$accountId || !preg_match('/^[A-Za-z0-9._-]{3,50}$/', $username)) {
    accountUpdateResponse(422, false, 'Enter a valid account and username.');
}
if (!in_array($role, ['staff', 'intern'], true)) {
    accountUpdateResponse(422, false, 'Select a valid account role.');
}

$duplicate = $conn->prepare('SELECT 1 FROM staff_accounts WHERE username = ? AND id <> ? LIMIT 1');
$duplicate->bind_param('si', $username, $accountId);
$duplicate->execute();
$usernameExists = (bool) $duplicate->get_result()->fetch_row();
$duplicate->close();
if ($usernameExists) {
    accountUpdateResponse(409, false, 'Username is already in use.');
}

$statement = $conn->prepare('UPDATE staff_accounts SET username = ?, role = ? WHERE id = ?');
$statement->bind_param('ssi', $username, $role, $accountId);
$statement->execute();
$affected = $statement->affected_rows;
$statement->close();

if ($affected === 0) {
    $exists = $conn->prepare('SELECT 1 FROM staff_accounts WHERE id = ? LIMIT 1');
    $exists->bind_param('i', $accountId);
    $exists->execute();
    $found = (bool) $exists->get_result()->fetch_row();
    $exists->close();
    if (!$found) accountUpdateResponse(404, false, 'Account not found.');
}

accountUpdateResponse(200, true, 'Account updated successfully.');
