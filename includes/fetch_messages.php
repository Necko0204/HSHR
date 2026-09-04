<?php
declare(strict_types=1);

require_once __DIR__ . '/admin_session.php';
header('Content-Type: text/html; charset=utf-8');

if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    echo '<div class="hshr-conversation-state hshr-error-state"><p>Authentication required.</p></div>';
    exit;
}

$receiverId = trim((string) ($_GET['receiver_id'] ?? ''));
$receiverRole = (string) ($_GET['receiver_role'] ?? '');
if ($receiverId === '' || !in_array($receiverRole, ['Administrator', 'staff'], true)) {
    http_response_code(422);
    echo '<div class="hshr-conversation-state hshr-error-state"><p>Select a valid conversation.</p></div>';
    exit;
}

require_once __DIR__ . '/db_config.php';
$senderId = (string) $_SESSION['admin_id'];
$senderRole = 'Administrator';
$statement = $conn->prepare(
    "SELECT m.message, m.sent_at,
            CASE WHEN m.sender_id = ? AND m.sender_type = ? THEN 'sent' ELSE 'received' END AS message_type,
            COALESCE(a.profile_picture, sa.profile_picture, 'uploads/profile_pictures/default.jpg') AS profile_picture
     FROM messages m
     LEFT JOIN admin a ON a.id = m.sender_id AND m.sender_type = 'Administrator'
     LEFT JOIN staff_accounts sa ON sa.employee_id = m.sender_id AND m.sender_type = 'staff'
     WHERE (m.sender_id = ? AND m.sender_type = ? AND m.receiver_id = ? AND m.receiver_type = ?)
        OR (m.sender_id = ? AND m.sender_type = ? AND m.receiver_id = ? AND m.receiver_type = ?)
     ORDER BY m.sent_at ASC"
);
$statement->bind_param(
    'ssssssssss',
    $senderId, $senderRole, $senderId, $senderRole, $receiverId,
    $receiverRole, $receiverId, $receiverRole, $senderId, $senderRole
);
$statement->execute();
$messages = $statement->get_result();

if ($messages->num_rows === 0) {
    echo '<div class="hshr-conversation-state"><i class="fa-regular fa-comments"></i><p>No messages yet. Start the conversation.</p></div>';
} else {
    while ($row = $messages->fetch_assoc()) {
        $sent = $row['message_type'] === 'sent';
        $message = htmlspecialchars((string) $row['message'], ENT_QUOTES, 'UTF-8');
        $timestamp = htmlspecialchars(date('g:i A', strtotime((string) $row['sent_at'])), ENT_QUOTES, 'UTF-8');
        $picture = htmlspecialchars(hshr_profile_picture_url($row['profile_picture'] ?? null), ENT_QUOTES, 'UTF-8');
        $side = $sent ? 'hshr-message-sent' : 'hshr-message-received';
        echo '<div class="hshr-chat-message ' . $side . '">';
        if (!$sent) echo '<img src="' . $picture . '" alt="" loading="lazy">';
        echo '<div class="message-container"><p>' . nl2br($message) . '</p><time>' . $timestamp . '</time></div></div>';
    }
}
$statement->close();

$seen = $conn->prepare(
    "UPDATE messages SET status = 'seen'
     WHERE sender_id = ? AND sender_type = ? AND receiver_id = ? AND receiver_type = ? AND status <> 'seen'"
);
$seen->bind_param('ssss', $receiverId, $receiverRole, $senderId, $senderRole);
$seen->execute();
$seen->close();
