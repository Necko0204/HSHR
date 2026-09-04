<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_api.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}
require_once __DIR__ . '/db_config.php';
$senderId = (string) $_SESSION['admin_id'];
$senderRole = 'Administrator';
$receiverId = trim((string) ($_POST['receiver_id'] ?? ''));
$receiverRole = (string) ($_POST['receiver_role'] ?? '');
$message = trim((string) ($_POST['message'] ?? ''));

if ($receiverId === '' || !in_array($receiverRole, ['Administrator', 'staff'], true)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'A valid recipient is required']);
    exit;
}
if ($message === '' || mb_strlen($message) > 4000) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Message must contain 1 to 4000 characters']);
    exit;
}

$recipientTable = $receiverRole === 'Administrator' ? 'admin' : 'staff_accounts';
$recipientColumn = $receiverRole === 'Administrator' ? 'id' : 'employee_id';
$recipient = $conn->prepare("SELECT 1 FROM {$recipientTable} WHERE {$recipientColumn} = ? LIMIT 1");
$recipient->bind_param('s', $receiverId);
$recipient->execute();
$recipientExists = (bool) $recipient->get_result()->fetch_row();
$recipient->close();
if (!$recipientExists) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Recipient not found']);
    exit;
}

$messageId = 'MSG-' . bin2hex(random_bytes(12));
$statement = $conn->prepare(
    "INSERT INTO messages (id, sender_id, sender_type, receiver_id, receiver_type, message, status)
     VALUES (?, ?, ?, ?, ?, ?, 'sent')"
);
$statement->bind_param('ssssss', $messageId, $senderId, $senderRole, $receiverId, $receiverRole, $message);
$saved = $statement->execute();
$statement->close();
if (!$saved) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Message could not be sent']);
    exit;
}

http_response_code(201);
echo json_encode(['success' => true, 'id' => $messageId]);
