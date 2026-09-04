<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_api.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}
require_once __DIR__ . '/db_config.php';
$payload = $_POST;
if (!$payload) {
    $decoded = json_decode((string) file_get_contents('php://input'), true);
    $payload = is_array($decoded) ? $decoded : [];
}
if (!array_key_exists('sidebarOn', $payload)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'sidebarOn is required']);
    exit;
}

$sidebarOn = filter_var($payload['sidebarOn'], FILTER_VALIDATE_BOOL) ? 1 : 0;
$adminId = (string) $_SESSION['admin_id'];
$statement = $conn->prepare('UPDATE admin SET sidebarOn = ? WHERE id = ?');
if (!$statement) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Unable to save navigation state']);
    exit;
}
$statement->bind_param('is', $sidebarOn, $adminId);
$saved = $statement->execute();
$statement->close();
if (!$saved) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Unable to save navigation state']);
    exit;
}

$_SESSION['sidebarOn'] = $sidebarOn;
echo json_encode(['success' => true, 'sidebarOn' => $sidebarOn]);
