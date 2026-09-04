<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_api.php';
require_once __DIR__ . '/../db_config.php';

function deductionUpdateResponse(int $status, bool $success, string $message): never
{
    http_response_code($status);
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') deductionUpdateResponse(405, false, 'Method not allowed.');
$id = trim((string) ($_POST['id'] ?? ''));
$name = trim((string) ($_POST['name'] ?? ''));
$description = trim((string) ($_POST['description'] ?? ''));
$type = trim((string) ($_POST['deduction_type'] ?? ''));
$amount = filter_var($_POST['amount'] ?? null, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
$percentage = filter_var($_POST['percentage'] ?? null, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
$maxCap = filter_var($_POST['max_cap'] ?? null, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
$mandatory = filter_var($_POST['is_mandatory'] ?? false, FILTER_VALIDATE_BOOL) ? 1 : 0;

if (!preg_match('/^HSHI-DED\d{10}$/', $id) || $name === '' || mb_strlen($name) > 120 || mb_strlen($description) > 500 || !in_array($type, ['fixed', 'percentage'], true)) {
    deductionUpdateResponse(422, false, 'Enter valid deduction details.');
}
if ($type === 'fixed' && ($amount === null || $amount <= 0)) deductionUpdateResponse(422, false, 'Fixed deductions require an amount greater than zero.');
if ($type === 'percentage' && ($percentage === null || $percentage <= 0 || $percentage > 100)) deductionUpdateResponse(422, false, 'Percentage must be greater than zero and at most 100.');
if ($maxCap !== null && $maxCap < 0) deductionUpdateResponse(422, false, 'Maximum cap cannot be negative.');
if ($type === 'fixed') $percentage = null;
if ($type === 'percentage') $amount = null;

$statement = $conn->prepare(
    'UPDATE deductions SET name = ?, description = ?, deduction_type = ?, amount = ?, percentage = ?, max_cap = ?, is_mandatory = ? WHERE id = ?'
);
$statement->bind_param('sssdddis', $name, $description, $type, $amount, $percentage, $maxCap, $mandatory, $id);
$statement->execute();
$affected = $statement->affected_rows;
$statement->close();
if ($affected === 0) {
    $exists = $conn->prepare('SELECT 1 FROM deductions WHERE id = ? LIMIT 1');
    $exists->bind_param('s', $id);
    $exists->execute();
    $found = (bool) $exists->get_result()->fetch_row();
    $exists->close();
    if (!$found) deductionUpdateResponse(404, false, 'Deduction not found.');
}
deductionUpdateResponse(200, true, 'Deduction updated successfully.');
