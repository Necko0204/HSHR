<?php
declare(strict_types=1);

require_once __DIR__ . '/staff_session.php';
header('Content-Type: text/html; charset=utf-8');
if (empty($_SESSION['employee_id'])) {
    http_response_code(401);
    echo '<li class="dropdown-item text-muted">Authentication required.</li>';
    exit;
}

require_once __DIR__ . '/db_config.php';
$employeeId = (string) $_SESSION['employee_id'];
$statement = $conn->prepare(
    "SELECT m.id, m.message, m.sent_at,
            CASE WHEN m.sender_id = ? AND m.sender_type = 'staff' THEN m.receiver_id ELSE m.sender_id END AS user_id,
            CASE WHEN m.sender_id = ? AND m.sender_type = 'staff' THEN m.receiver_type ELSE m.sender_type END AS user_role
     FROM messages m
     WHERE (m.sender_id = ? AND m.sender_type = 'staff')
        OR (m.receiver_id = ? AND m.receiver_type = 'staff')
     ORDER BY m.sent_at DESC, m.id DESC LIMIT 200"
);
$statement->bind_param('ssss', $employeeId, $employeeId, $employeeId, $employeeId);
$statement->execute();
$messages = $statement->get_result();

$conversations = [];
while ($row = $messages->fetch_assoc()) {
    $role = (string) $row['user_role'];
    $id = (string) $row['user_id'];
    if (!in_array($role, ['Administrator', 'staff'], true) || $id === '') continue;
    $key = $role . '|' . $id;
    if (!isset($conversations[$key])) $conversations[$key] = $row;
    if (count($conversations) >= 20) break;
}
$statement->close();

if ($conversations === []) {
    echo '<li class="dropdown-item text-center text-muted">No messages</li>';
    exit;
}

$adminLookup = $conn->prepare('SELECT username, profile_picture FROM admin WHERE id = ? LIMIT 1');
$staffLookup = $conn->prepare('SELECT username, profile_picture FROM staff_accounts WHERE employee_id = ? LIMIT 1');
foreach ($conversations as $row) {
    $id = (string) $row['user_id'];
    $role = (string) $row['user_role'];
    $lookup = $role === 'Administrator' ? $adminLookup : $staffLookup;
    $lookup->bind_param('s', $id);
    $lookup->execute();
    $person = $lookup->get_result()->fetch_assoc() ?: [];
    $username = htmlspecialchars((string) ($person['username'] ?? 'Unknown user'), ENT_QUOTES, 'UTF-8');
    $picture = htmlspecialchars(hshr_profile_picture_url($person['profile_picture'] ?? null, '../'), ENT_QUOTES, 'UTF-8');
    $message = trim((string) $row['message']);
    $short = mb_strlen($message) > 45 ? mb_substr($message, 0, 45) . '…' : $message;
    $short = htmlspecialchars($short, ENT_QUOTES, 'UTF-8');
    $safeId = htmlspecialchars($id, ENT_QUOTES, 'UTF-8');
    $safeRole = htmlspecialchars($role, ENT_QUOTES, 'UTF-8');
    echo '<li class="dropdown-item d-flex align-items-center message-item" data-id="' . $safeId . '" data-role="' . $safeRole . '">'
        . '<img src="' . $picture . '" class="rounded-circle me-2" width="40" height="40" alt="">'
        . '<div><strong>' . $username . '</strong><p class="text-muted mb-0" style="font-size:12px">' . $short . '</p></div></li>';
}
$adminLookup->close();
$staffLookup->close();
