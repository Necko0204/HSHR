<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/admin_api.php';
require_once __DIR__ . '/db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
    exit;
}

function adminProfileResponse(int $status, string $type, string $message): never
{
    http_response_code($status);
    echo json_encode(['status' => $type, 'message' => $message]);
    exit;
}

$adminId = (string) $_SESSION['admin_id'];
$name = trim((string) ($_POST['name'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$username = trim((string) ($_POST['username'] ?? ''));
$age = filter_var($_POST['age'] ?? null, FILTER_VALIDATE_INT);
$bio = trim((string) ($_POST['bio'] ?? ''));
$phone = trim((string) ($_POST['phone'] ?? ''));
$experiences = trim((string) ($_POST['experiences'] ?? ''));
$address = trim((string) ($_POST['address'] ?? ''));

if ($name === '' || strlen($name) > 100) adminProfileResponse(422, 'error', 'Enter a valid name.');
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) adminProfileResponse(422, 'error', 'Enter a valid email address.');
if (!preg_match('/^[A-Za-z0-9._-]{3,50}$/', $username)) adminProfileResponse(422, 'error', 'Enter a valid username.');
if ($age === false || $age < 16 || $age > 120) adminProfileResponse(422, 'error', 'Enter a valid age.');
if (!preg_match('/^\+?[0-9 ()-]{7,20}$/', $phone)) adminProfileResponse(422, 'error', 'Enter a valid phone number.');
if (strlen($bio) > 2000 || strlen($experiences) > 4000 || strlen($address) > 2000) {
    adminProfileResponse(422, 'error', 'One or more profile fields are too long.');
}

$duplicate = $conn->prepare('SELECT 1 FROM admin WHERE username = ? AND id <> ? LIMIT 1');
$duplicate->bind_param('ss', $username, $adminId);
$duplicate->execute();
$usernameTaken = (bool) $duplicate->get_result()->fetch_row();
$duplicate->close();
if ($usernameTaken) adminProfileResponse(409, 'error', 'That username is already in use.');

$current = $conn->prepare('SELECT profile_picture FROM admin WHERE id = ? LIMIT 1');
$current->bind_param('s', $adminId);
$current->execute();
$profilePicture = $current->get_result()->fetch_assoc()['profile_picture'] ?? null;
$current->close();
if ($profilePicture === null) adminProfileResponse(404, 'error', 'Administrator account not found.');

$picture = $_FILES['profile_picture'] ?? null;
if (is_array($picture) && ($picture['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
    if (($picture['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || (int) ($picture['size'] ?? 0) > 5 * 1024 * 1024) {
        adminProfileResponse(422, 'error', 'Profile image must be smaller than 5 MB.');
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file((string) $picture['tmp_name']);
    $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
    if (!isset($extensions[$mime])) adminProfileResponse(422, 'error', 'Profile image must be JPEG, PNG, GIF, or WebP.');

    $directory = __DIR__ . '/uploads/profile_pictures';
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        adminProfileResponse(500, 'error', 'Profile image storage is unavailable.');
    }
    $filename = 'admin_' . bin2hex(random_bytes(12)) . '.' . $extensions[$mime];
    if (!move_uploaded_file((string) $picture['tmp_name'], $directory . '/' . $filename)) {
        adminProfileResponse(500, 'error', 'Profile image could not be saved.');
    }
    $profilePicture = 'uploads/profile_pictures/' . $filename;
}

$statement = $conn->prepare(
    "UPDATE admin
     SET name = ?, email = ?, username = ?, profile_picture = ?, age = ?, bio = ?, phone = ?, experiences = ?, address = ?
     WHERE id = ? AND position = 'Administrator'"
);
$statement->bind_param('ssssisssss', $name, $email, $username, $profilePicture, $age, $bio, $phone, $experiences, $address, $adminId);
$statement->execute();
$statement->close();

$_SESSION['admin_username'] = $username;
adminProfileResponse(200, 'success', 'Profile updated successfully.');
