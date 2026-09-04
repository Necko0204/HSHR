<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/admin_api.php';
require_once __DIR__ . '/db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
    exit;
}

$employeeId = trim((string) ($_POST['employee_id'] ?? ''));
$username = trim((string) ($_POST['username'] ?? ''));
$password = (string) ($_POST['password'] ?? '');
$role = strtolower(trim((string) ($_POST['role'] ?? 'staff')));

if ($employeeId === '' || $username === '' || $password === '') {
    http_response_code(422);
    echo json_encode(['status' => 'error', 'message' => 'Employee, username, and password are required.']);
    exit;
}
if (!preg_match('/^[A-Za-z0-9._-]{3,50}$/', $username) || strlen($password) < 8 || strlen($password) > 200 || !in_array($role, ['staff', 'intern'], true)) {
    http_response_code(422);
    echo json_encode(['status' => 'error', 'message' => 'Use a valid username and a password of at least 8 characters.']);
    exit;
}

$profilePath = null;
$conn->begin_transaction();
try {
    $employee = $conn->prepare('SELECT id FROM employees WHERE id = ? LIMIT 1 FOR UPDATE');
    $employee->bind_param('s', $employeeId);
    $employee->execute();
    $employeeFound = (bool) $employee->get_result()->fetch_row();
    $employee->close();
    if (!$employeeFound) {
        $conn->rollback();
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Employee record not found.']);
        exit;
    }

    $accountCheck = $conn->prepare('SELECT 1 FROM staff_accounts WHERE employee_id = ? LIMIT 1');
    $accountCheck->bind_param('s', $employeeId);
    $accountCheck->execute();
    $accountExists = (bool) $accountCheck->get_result()->fetch_row();
    $accountCheck->close();
    if ($accountExists) {
        $conn->rollback();
        http_response_code(409);
        echo json_encode(['status' => 'error', 'message' => 'This employee already has an account.']);
        exit;
    }

    $usernameCheck = $conn->prepare('SELECT 1 FROM staff_accounts WHERE username = ? LIMIT 1');
    $usernameCheck->bind_param('s', $username);
    $usernameCheck->execute();
    $usernameExists = (bool) $usernameCheck->get_result()->fetch_row();
    $usernameCheck->close();
    if ($usernameExists) {
        $conn->rollback();
        http_response_code(409);
        echo json_encode(['status' => 'error', 'message' => 'Username is already taken.']);
        exit;
    }

$picture = $_FILES['profile_picture'] ?? null;
if (is_array($picture) && ($picture['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
    if ($picture['error'] !== UPLOAD_ERR_OK || (int) $picture['size'] > 5 * 1024 * 1024 || !is_uploaded_file((string) $picture['tmp_name'])) {
        http_response_code(422);
        echo json_encode(['status' => 'error', 'message' => 'Profile image must be smaller than 5 MB.']);
        $conn->rollback();
        exit;
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($picture['tmp_name']);
    $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
    if (!isset($extensions[$mime])) {
        http_response_code(422);
        echo json_encode(['status' => 'error', 'message' => 'Profile image must be JPEG, PNG, GIF, or WebP.']);
        $conn->rollback();
        exit;
    }

    $directory = __DIR__ . '/uploads/profile_pictures';
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Profile image storage is unavailable.']);
        $conn->rollback();
        exit;
    }
    $filename = 'profile_' . bin2hex(random_bytes(12)) . '.' . $extensions[$mime];
    if (!move_uploaded_file($picture['tmp_name'], $directory . '/' . $filename)) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Profile image could not be saved.']);
        $conn->rollback();
        exit;
    }
    $profilePath = 'uploads/profile_pictures/' . $filename;
}

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $statement = $conn->prepare(
        'INSERT INTO staff_accounts (employee_id, username, password, role, profile_picture)
         VALUES (?, ?, ?, ?, ?)'
    );
    $statement->bind_param('sssss', $employeeId, $username, $passwordHash, $role, $profilePath);
    $statement->execute();
    $statement->close();
    $conn->commit();
} catch (Throwable $error) {
    $conn->rollback();
    if ($profilePath !== null && is_file(__DIR__ . '/' . $profilePath)) @unlink(__DIR__ . '/' . $profilePath);
    error_log('Staff account creation failed: ' . $error->getMessage());
    http_response_code($error instanceof mysqli_sql_exception && (int) $error->getCode() === 1062 ? 409 : 500);
    echo json_encode(['status' => 'error', 'message' => 'Account could not be created. Verify that the employee and username are not already in use.']);
    exit;
}

http_response_code(201);
echo json_encode(['status' => 'success', 'message' => 'Employee account created successfully.']);
