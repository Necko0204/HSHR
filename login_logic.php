<?php
require_once __DIR__ . '/includes/admin_session.php';

header('Content-Type: application/json; charset=utf-8');
require_once('db_config.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'This endpoint requires a POST request.']);
    exit;
}

if (!hshr_validate_csrf()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Refresh the sign-in page and try again.']);
    exit;
}

$username = trim((string) ($_POST['username'] ?? ''));
$password = (string) ($_POST['password'] ?? '');
$honeypot = trim((string) ($_POST['website'] ?? ''));

if ($honeypot !== '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Unable to process this request.']);
    exit;
}

if ($username === '' || $password === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Username and password are required.']);
    exit;
}

if (!hshr_rate_limit_consume('admin-login', $username, 5, 900)) {
    http_response_code(429);
    header('Retry-After: 900');
    echo json_encode(['success' => false, 'message' => 'Too many sign-in attempts. Try again in 15 minutes.']);
    exit;
}

$stmt = $conn->prepare("SELECT id, username, position, sidebarOn, darkmodeOn, password FROM admin WHERE username = ?");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Sign in is temporarily unavailable.']);
    exit;
}
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $storedHash = (string) $row['password'];

    if (hshr_verify_password($password, $storedHash)) {
        if (hshr_password_needs_upgrade($storedHash)) {
            $upgradedHash = password_hash($password, PASSWORD_DEFAULT);
            $upgrade = $conn->prepare('UPDATE admin SET password = ? WHERE id = ?');
            $upgrade->bind_param('ss', $upgradedHash, $row['id']);
            $upgrade->execute();
            $upgrade->close();
        }

        session_regenerate_id(true);
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['admin_username'] = $row['username'];
        $_SESSION['position'] = $row['position'];
        $_SESSION['sidebarOn'] = $row['sidebarOn'];
        $_SESSION['darkmodeOn'] = $row['darkmodeOn'];
        $_SESSION['login_time'] = time();
        hshr_mark_authenticated_session();
        hshr_rate_limit_reset('admin-login', $username);
        session_write_close();

        echo json_encode(['success' => true, 'message' => 'Login successful!']);
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
    }
} else {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
}

$stmt->close();
$conn->close();
