<?php
session_name('admin_session');  
session_start();  

include 'db_config.php';  

// Validate session variables
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['position'])) {
    echo "<p class='text-muted text-center'>Error: Not logged in</p>";
    exit;
}
$sql = "SELECT * FROM levels WHERE status = 'Active' ORDER BY level_id ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0): ?>


        <div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
    
            <div style="max-height: 530px; overflow-y: auto;">
                <table class="table table-borderless table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width: 100px;">Level ID</th>
                            <th style="min-width: 150px;">Level Name</th>
                            <th style="min-width: 150px;">Status</th>
                            <th style="min-width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="levelsTable">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row["level_id"]) ?></td>
                                <td><?= htmlspecialchars($row["level_name"]) ?></td>
                                <td>
                                    <span class="badge <?= $row["status"] == 'Active' ? 'bg-gradient-success text-white' : 'bg-gradient-danger text-white' ?>">
                                        <?= htmlspecialchars($row["status"]) ?>
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#EditLevelModal" data-id="<?= htmlspecialchars($row["level_id"]) ?>" data-name="<?= htmlspecialchars($row["level_name"]) ?>" data-status="<?= htmlspecialchars($row["status"]) ?>">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#DeactivateLevelModal" data-id="<?= htmlspecialchars($row["level_id"]) ?>">
                                        <i class="fa fa-ban"></i> Deactivate
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            
      
            <?php else: ?>
                <p>No active levels found.</p>
            <?php endif;
$conn->close();
?>