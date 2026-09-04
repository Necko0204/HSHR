<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/staff_api.php';
require_once __DIR__ . '/db_config.php';

$choice = strtolower(trim((string) ($_POST['background_choice'] ?? 'none')));
if (!in_array($choice, ['none', 'particles', 'clouds', 'stars'], true)) {
    staff_api_respond(422, false, 'Unknown background choice.');
}
$enabled = filter_var($_POST['background_enabled'] ?? false, FILTER_VALIDATE_BOOL) ? 1 : 0;

$statement = $conn->prepare(
    'INSERT INTO staff_background_settings (employee_id, background_choice, background_enabled)
     VALUES (?, ?, ?)
     ON DUPLICATE KEY UPDATE background_choice = VALUES(background_choice), background_enabled = VALUES(background_enabled)'
);
$statement->bind_param('ssi', $staffApiEmployeeId, $choice, $enabled);
$statement->execute();
$statement->close();

staff_api_respond(200, true, 'Background settings updated successfully.');
