<?php
require_once __DIR__ . '/admin_session.php';
if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    exit;
}
require 'db_config.php';

$query = "SELECT * FROM staff_accounts";
$result = $conn->query($query);

$output = "";
while ($row = $result->fetch_assoc()) {
    $safe = [];
    foreach (['id', 'employee_id', 'username', 'role', 'status'] as $field) {
        $safe[$field] = htmlspecialchars((string) ($row[$field] ?? ''), ENT_QUOTES, 'UTF-8');
    }
    $picture = (string) ($row['profile_picture'] ?? '');
    if (!str_starts_with($picture, 'uploads/profile_pictures/') || str_contains($picture, '..') || preg_match('/^[A-Za-z0-9._\/-]+$/', $picture) !== 1) {
        $picture = '';
    }
    $profilePicture = $picture !== ''
        ? "<img src='" . htmlspecialchars($picture, ENT_QUOTES, 'UTF-8') . "' alt='Profile' class='img-thumbnail' width='100' height='100' style='object-fit: cover;'>"
        : "<span>No Image</span>";

    $active = strtolower((string) $row['status']) === 'active';
    $statusBadge = $active ?
        '<span class="badge bg-success">Active</span>' :
        '<span class="badge bg-danger">Inactive</span>';

    $toggleBtn = $active
        ? '<button class="btn btn-danger btn-sm toggle-status-btn" data-id="' . $safe['id'] . '" data-status="Deactivate"><i class="fas fa-times"></i> Deactivate</button>'
        : '<button class="btn btn-success btn-sm toggle-status-btn" data-id="' . $safe['id'] . '" data-status="Reactivate"><i class="fas fa-check"></i> Reactivate</button>';

    $output .= "<tr>
        <td style='display:none;'>{$safe['id']}</td>
        <td>$profilePicture</td>
        <td>{$safe['employee_id']}</td>
        <td>{$safe['username']}</td>
        <td>{$safe['role']}</td>
        <td>$statusBadge</td>
        <td>
            <button class='btn btn-warning btn-sm edit-btn'
                data-id='{$safe['id']}'
                data-employee_id='{$safe['employee_id']}'
                data-username='{$safe['username']}'
                data-role='{$safe['role']}'
                data-status='{$safe['status']}'>
                <i class='fas fa-edit'></i> Edit
            </button>
            $toggleBtn
        </td>
    </tr>";
}

echo $output;
?>
