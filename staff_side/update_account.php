<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/staff_api.php';
require_once __DIR__ . '/db_config.php';

$username = trim((string) ($_POST['username'] ?? ''));
$password = (string) ($_POST['password'] ?? '');
if (!preg_match('/^[A-Za-z0-9._-]{3,50}$/', $username)) {
    staff_api_respond(422, false, 'Use a username containing 3 to 50 letters, numbers, dots, underscores, or hyphens.');
}
if (strlen($password) < 8 || strlen($password) > 200) {
    staff_api_respond(422, false, 'Password must contain between 8 and 200 characters.');
}

$duplicate = $conn->prepare('SELECT 1 FROM staff_accounts WHERE username = ? AND employee_id <> ? LIMIT 1');
$duplicate->bind_param('ss', $username, $staffApiEmployeeId);
$duplicate->execute();
$usernameTaken = (bool) $duplicate->get_result()->fetch_row();
$duplicate->close();
if ($usernameTaken) {
    staff_api_respond(409, false, 'That username is already in use.');
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$statement = $conn->prepare('UPDATE staff_accounts SET username = ?, password = ? WHERE employee_id = ?');
$statement->bind_param('sss', $username, $passwordHash, $staffApiEmployeeId);
$statement->execute();
$updated = $statement->affected_rows === 1;
$statement->close();

if (!$updated) {
    staff_api_respond(404, false, 'The staff account could not be found.');
}

$_SESSION['username'] = $username;
staff_api_respond(200, true, 'Account credentials updated successfully.');
