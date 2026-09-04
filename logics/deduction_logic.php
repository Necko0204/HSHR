<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_api.php';
require_once __DIR__ . '/../db_config.php';

function deductionCreateResponse(int $status, string $type, string $message): never
{
    http_response_code($status);
    echo json_encode(['status' => $type, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') deductionCreateResponse(405, 'error', 'Method not allowed.');
$name = trim((string) ($_POST['deductionName'] ?? ''));
$description = trim((string) ($_POST['deductionDescription'] ?? ''));
$type = trim((string) ($_POST['deductionType'] ?? ''));
$amount = filter_var($_POST['deductionAmount'] ?? null, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
$percentage = filter_var($_POST['deductionPercentage'] ?? null, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
$maxCap = filter_var($_POST['deductionMaxCap'] ?? null, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
$mandatory = filter_var($_POST['isMandatory'] ?? false, FILTER_VALIDATE_BOOL) ? 1 : 0;

if ($name === '' || mb_strlen($name) > 120 || mb_strlen($description) > 500 || !in_array($type, ['fixed', 'percentage'], true)) {
    deductionCreateResponse(422, 'error', 'Enter a valid deduction name, description, and type.');
}
if ($type === 'fixed' && ($amount === null || $amount <= 0)) {
    deductionCreateResponse(422, 'error', 'Fixed deductions require an amount greater than zero.');
}
if ($type === 'percentage' && ($percentage === null || $percentage <= 0 || $percentage > 100)) {
    deductionCreateResponse(422, 'error', 'Percentage deductions must be greater than zero and at most 100.');
}
if ($maxCap !== null && $maxCap < 0) deductionCreateResponse(422, 'error', 'Maximum cap cannot be negative.');
if ($type === 'fixed') $percentage = null;
if ($type === 'percentage') $amount = null;

$locked = false;
$conn->begin_transaction();
try {
    $lockResult = $conn->query("SELECT GET_LOCK('hshr_deduction_sequence', 5) AS acquired");
    $locked = (int) ($lockResult->fetch_assoc()['acquired'] ?? 0) === 1;
    if (!$locked) throw new RuntimeException('Deduction sequence is busy.');
    $prefix = 'HSHI-DED' . date('Y');
    $last = $conn->prepare('SELECT id FROM deductions WHERE id LIKE CONCAT(?, \'%\') ORDER BY id DESC LIMIT 1 FOR UPDATE');
    $last->bind_param('s', $prefix);
    $last->execute();
    $lastId = $last->get_result()->fetch_assoc()['id'] ?? null;
    $last->close();
    $number = $lastId ? ((int) substr((string) $lastId, -6) + 1) : 1;
    $id = $prefix . str_pad((string) $number, 6, '0', STR_PAD_LEFT);

    $insert = $conn->prepare(
        'INSERT INTO deductions (id, name, description, deduction_type, amount, percentage, max_cap, is_mandatory)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $insert->bind_param('ssssdddi', $id, $name, $description, $type, $amount, $percentage, $maxCap, $mandatory);
    $insert->execute();
    $insert->close();
    $conn->commit();
    try { $conn->query("SELECT RELEASE_LOCK('hshr_deduction_sequence')"); } catch (Throwable $ignored) {}
    deductionCreateResponse(201, 'success', 'Deduction added successfully.');
} catch (Throwable $error) {
    $conn->rollback();
    if ($locked) try { $conn->query("SELECT RELEASE_LOCK('hshr_deduction_sequence')"); } catch (Throwable $ignored) {}
    error_log('Deduction creation failed: ' . $error->getMessage());
    deductionCreateResponse(500, 'error', 'Deduction could not be created.');
}
