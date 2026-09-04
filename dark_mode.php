<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/admin_api.php';
require_once __DIR__ . '/db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
    exit;
}

$darkMode = filter_var($_POST['dark_mode'] ?? false, FILTER_VALIDATE_BOOL) ? 1 : 0;
$adminId = (string) $_SESSION['admin_id'];
$statement = $conn->prepare('UPDATE admin SET darkmodeOn = ? WHERE id = ?');
$statement->bind_param('is', $darkMode, $adminId);
$statement->execute();
$statement->close();

$_SESSION['darkmodeOn'] = $darkMode;
$_SESSION['dark_mode'] = $darkMode;
echo json_encode(['success' => true, 'dark_mode' => $darkMode]);
