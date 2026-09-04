<?php
require_once __DIR__ . '/admin_session.php';
if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    exit;
}
require_once 'db_config.php'; // Adjust based on your structure

// Fetch deductions sorted by created_at descending
$sql = "SELECT id, name, description, deduction_type, amount, percentage, max_cap, is_mandatory, created_at FROM deductions ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td class="hidden-id" style="display: none;"><?= htmlspecialchars((string) $row['id'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($row["name"]) ?></td>
            <td><?= htmlspecialchars($row["description"]) ?></td>
            <td><?= htmlspecialchars(ucfirst($row["deduction_type"])) ?></td>
            <td><?= $row["deduction_type"] == "fixed" ? number_format($row["amount"], 2) : '-' ?></td>
            <td><?= $row["deduction_type"] == "percentage" ? $row["percentage"] . "%" : '-' ?></td>
            <td><?= $row["max_cap"] ? number_format($row["max_cap"], 2) : 'N/A' ?></td>
            <td><?= $row["is_mandatory"] ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-danger">No</span>' ?></td>
        </tr>
        <?php
    endwhile;
} else {
    echo '<tr><td colspan="8" class="text-center">No deductions found.</td></tr>';
}

$conn->close();
?>
