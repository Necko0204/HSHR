<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/staff_api.php';
require_once __DIR__ . '/db_config.php';

$firstname = trim((string) ($_POST['firstname'] ?? ''));
$lastname = trim((string) ($_POST['lastname'] ?? ''));
$email = trim((string) ($_POST['email1'] ?? ''));
$contactNumber = trim((string) ($_POST['mobilephone'] ?? ''));
$homeAddress = trim((string) ($_POST['homeaddress'] ?? ''));
$maritalStatus = trim((string) ($_POST['maritalstatus'] ?? ''));

if ($firstname === '' || mb_strlen($firstname) > 100 || $lastname === '' || mb_strlen($lastname) > 100) {
    staff_api_respond(422, false, 'First and last name are required and must be shorter than 100 characters.');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
    staff_api_respond(422, false, 'Enter a valid email address.');
}
if (!preg_match('/^\+?[0-9 ()-]{7,20}$/', $contactNumber)) {
    staff_api_respond(422, false, 'Enter a valid contact number.');
}
if ($homeAddress === '' || mb_strlen($homeAddress) > 1000) {
    staff_api_respond(422, false, 'Home address is required and must be shorter than 1,000 characters.');
}
if (!in_array($maritalStatus, ['Single', 'Married', 'Divorced', 'Widowed'], true)) {
    staff_api_respond(422, false, 'Select a valid marital status.');
}

$profilePath = null;
$profileDiskPath = null;
$picture = $_FILES['profile_picture'] ?? null;
if (is_array($picture) && ($picture['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
    if (($picture['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK
        || (int) ($picture['size'] ?? 0) > 5 * 1024 * 1024
        || !is_uploaded_file((string) ($picture['tmp_name'] ?? ''))) {
        staff_api_respond(422, false, 'Profile image must be a valid upload smaller than 5 MB.');
    }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file((string) $picture['tmp_name']);
    $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
    if (!isset($extensions[$mime])) {
        staff_api_respond(422, false, 'Profile image must be JPEG, PNG, GIF, or WebP.');
    }
    $directory = dirname(__DIR__) . '/uploads/profile_pictures';
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        staff_api_respond(500, false, 'Profile image storage is unavailable.');
    }
    $filename = 'profile_' . bin2hex(random_bytes(12)) . '.' . $extensions[$mime];
    $profileDiskPath = $directory . '/' . $filename;
    if (!move_uploaded_file((string) $picture['tmp_name'], $profileDiskPath)) {
        staff_api_respond(500, false, 'Profile image could not be saved.');
    }
    $profilePath = 'uploads/profile_pictures/' . $filename;
}

$conn->begin_transaction();
try {
    $employee = $conn->prepare(
        'UPDATE employees SET firstname = ?, lastname = ?, email1 = ?, mobilephone = ?, homeaddress = ?, maritalstatus = ? WHERE id = ?'
    );
    $employee->bind_param('sssssss', $firstname, $lastname, $email, $contactNumber, $homeAddress, $maritalStatus, $staffApiEmployeeId);
    $employee->execute();
    $employee->close();

    if ($profilePath !== null) {
        $account = $conn->prepare('UPDATE staff_accounts SET profile_picture = ? WHERE employee_id = ?');
        $account->bind_param('ss', $profilePath, $staffApiEmployeeId);
        $account->execute();
        $account->close();
    }
    $conn->commit();
} catch (Throwable $error) {
    $conn->rollback();
    if ($profileDiskPath !== null && is_file($profileDiskPath)) @unlink($profileDiskPath);
    error_log('Staff profile update failed: ' . $error->getMessage());
    staff_api_respond(500, false, 'Profile could not be updated.');
}

$extra = $profilePath === null ? [] : ['profile_picture' => '../' . $profilePath];
staff_api_respond(200, true, 'Profile updated successfully.', $extra);
