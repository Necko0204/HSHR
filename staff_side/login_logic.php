<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/staff_session.php';
require_once __DIR__ . '/db_config.php';

header('Content-Type: application/json; charset=utf-8');

function staffLoginResponse(int $status, string $type, string $message, array $extra = []): never
{
    http_response_code($status);
    echo json_encode(['status' => $type, 'message' => $message] + $extra);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    staffLoginResponse(405, 'error', 'This endpoint requires a POST request.');
}
if (!hshr_validate_csrf()) {
    staffLoginResponse(403, 'error', 'Refresh the sign-in page and try again.');
}

$username = trim((string) ($_POST['username'] ?? ''));
$password = (string) ($_POST['password'] ?? '');
$honeypot = trim((string) ($_POST['website'] ?? ''));
if ($honeypot !== '') {
    staffLoginResponse(400, 'error', 'Unable to process this request.');
}
if ($username === '' || $password === '') {
    staffLoginResponse(422, 'error', 'Username and password are required.');
}
if (!hshr_rate_limit_consume('staff-login', $username, 5, 900)) {
    header('Retry-After: 900');
    staffLoginResponse(429, 'error', 'Too many sign-in attempts. Try again in 15 minutes.');
}

$statement = $conn->prepare(
    "SELECT sa.id, sa.employee_id, sa.password, sa.role
     FROM staff_accounts sa
     JOIN employees e ON e.id = sa.employee_id
     WHERE sa.username = ? AND LOWER(sa.status) = 'active' AND e.status = 'Active'
     LIMIT 1"
);
$statement->bind_param('s', $username);
$statement->execute();
$account = $statement->get_result()->fetch_assoc();
$statement->close();

if (!$account || !hshr_verify_password($password, (string) $account['password'])) {
    staffLoginResponse(401, 'error', 'Invalid username or password.');
}

$role = strtolower((string) $account['role']);
if (!in_array($role, ['staff', 'intern'], true)) {
    staffLoginResponse(403, 'error', 'This account is not permitted to use the staff portal.');
}

if (hshr_password_needs_upgrade((string) $account['password'])) {
    $upgradedHash = password_hash($password, PASSWORD_DEFAULT);
    $upgrade = $conn->prepare('UPDATE staff_accounts SET password = ? WHERE id = ?');
    $upgrade->bind_param('si', $upgradedHash, $account['id']);
    $upgrade->execute();
    $upgrade->close();
}

session_regenerate_id(true);
$_SESSION['employee_id'] = (string) $account['employee_id'];
$_SESSION['username'] = $username;
$_SESSION['role'] = $role;
hshr_mark_authenticated_session();
hshr_rate_limit_reset('staff-login', $username);
$conn->close();

staffLoginResponse(200, 'success', 'Login successful.', ['redirect' => 'dashboard.php']);
