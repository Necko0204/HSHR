<?php
require_once __DIR__ . '/admin_session.php';
include 'db_config.php';

// Validate session variables
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['position'])) {
    echo "<p class='text-muted text-center'>Error: Not logged in</p>";
    exit;
}

// Fetch inactive levels
$sql = "SELECT * FROM levels WHERE status = 'Inactive' ORDER BY level_id ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0): ?>
    <table class="table table-borderless table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th style="min-width: 100px;">Level ID</th>
                <th style="min-width: 150px;">Level Name</th>
                <th style="min-width: 150px;">Status</th>
                <th style="min-width: 150px;">Actions</th>
            </tr>
        </thead>
        <tbody id="inactiveLevelsTable">
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row["level_id"]) ?></td>
                    <td><?= htmlspecialchars($row["level_name"]) ?></td>
                    <td>
                        <span class="badge bg-gradient-danger text-white">
                            <?= htmlspecialchars($row["status"]) ?>
                        </span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-success btn-sm" onclick="reactivateLevel(<?= htmlspecialchars($row['level_id']) ?>)">
                            <i class="fa fa-check"></i> Reactivate
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No inactive levels found.</p>
<?php endif;

$conn->close();
?>

<script>
function reactivateLevel(levelId) {
    $.post('logics/reactivate_level.php', { level_id: levelId }, function(response) {
        toastr.clear();
        if (response.success) {
            toastr.success('Level reactivated successfully!');
            $('#inactiveLevelsTable').load('includes/fetch_inactive_levels.php');
        } else {
            toastr.error('Failed to reactivate level.');
        }
    }, 'json');
}
</script>
