<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_api.php';
require_once __DIR__ . '/../db_config.php';

function levelCreateResponse(int $status, string $type, string $message): never
{
    http_response_code($status);
    echo json_encode(['status' => $type, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') levelCreateResponse(405, 'error', 'Method not allowed.');
$levelName = trim((string) ($_POST['levelName'] ?? ''));
$levelStatus = trim((string) ($_POST['levelStatus'] ?? 'Active'));
if ($levelName === '' || mb_strlen($levelName) > 120 || !in_array($levelStatus, ['Active', 'Inactive'], true)) {
    levelCreateResponse(422, 'error', 'Enter a valid level name and status.');
}

$locked = false;
$conn->begin_transaction();
try {
    $lockResult = $conn->query("SELECT GET_LOCK('hshr_level_sequence', 5) AS acquired");
    $locked = (int) ($lockResult->fetch_assoc()['acquired'] ?? 0) === 1;
    if (!$locked) throw new RuntimeException('Level sequence is busy.');

    $prefix = 'HSHI-LVL' . date('Y');
    $last = $conn->prepare('SELECT level_id FROM levels WHERE level_id LIKE CONCAT(?, \'%\') ORDER BY level_id DESC LIMIT 1 FOR UPDATE');
    $last->bind_param('s', $prefix);
    $last->execute();
    $lastId = $last->get_result()->fetch_assoc()['level_id'] ?? null;
    $last->close();
    $number = $lastId ? ((int) substr((string) $lastId, -7) + 1) : 1;
    $levelId = $prefix . str_pad((string) $number, 7, '0', STR_PAD_LEFT);

    $insert = $conn->prepare('INSERT INTO levels (level_id, level_name, status) VALUES (?, ?, ?)');
    $insert->bind_param('sss', $levelId, $levelName, $levelStatus);
    $insert->execute();
    $insert->close();
    $conn->commit();
    try { $conn->query("SELECT RELEASE_LOCK('hshr_level_sequence')"); } catch (Throwable $ignored) {}
    levelCreateResponse(201, 'success', 'Level added successfully.');
} catch (Throwable $error) {
    $conn->rollback();
    if ($locked) try { $conn->query("SELECT RELEASE_LOCK('hshr_level_sequence')"); } catch (Throwable $ignored) {}
    error_log('Level creation failed: ' . $error->getMessage());
    $status = $error instanceof mysqli_sql_exception && (int) $error->getCode() === 1062 ? 409 : 500;
    levelCreateResponse($status, 'error', $status === 409 ? 'A level with that name already exists.' : 'Level could not be created.');
}
