<?php
require_once __DIR__ . '/includes/admin_page.php';
include 'includes/breadcrumb.php';
include 'db_config.php';
include 'helper.php';

// Debug: Check if session is properly set
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/asdasdasd123123123123123.jpg">
    <title>Holy Spirit Human Resource</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="background.css">
</head>
<body>
    <!-- Sidebar & Navbar in a separate container -->
        <div class="main-container">
            <?php include 'sidebar.php'; ?>
        </div>
        <div class="content-container">
            <?php include 'nav_header.php'; ?>
        </div>

        <main class="wrapper">
                <?php
                echo generateBreadcrumb();
                ?>
    <!-- Title Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Level Management</h2>
        <button type="button" class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#AddLevelModal">
            <i class="fa fa-plus"></i> <span>Add Level</span>
        </button>
    </div>

<!-- Levels Management -->
<div class="card shadow-lg border-1 rounded-3">
    <div class="card-header bg-gradient-primary text-black d-flex justify-content-between align-items-center">
        <!-- Search Bar -->
        <div class="d-flex align-items-center">
            <input type="text" id="searchInput" class="form-control" placeholder="Search..." style="width: 150px;">
        </div>

        <!-- Archives Button -->
        <button type="button" class="btn btn-secondary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#ArchivesModal">
            <i class="fa fa-folder"></i> <span>Archives</span>
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
            <?php
            $sql = "SELECT level_id, level_name, status FROM levels";
            $result = $conn->query($sql);
            ?>
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
            </div>
            <?php $conn->close(); ?>
        </div>
    </div>
</div>
</main>

<!-- Add Level Modal -->
<div class="modal fade" id="AddLevelModal" tabindex="-1" aria-labelledby="AddLevelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="AddLevelModalLabel">Add Level</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addLevelForm">
                    <div class="mb-3">
                        <label for="levelName" class="form-label">Level Name</label>
                        <input type="text" class="form-control" id="levelName" name="levelName" required>
                    </div>
                    <div class="mb-3">
                        <label for="levelStatus" class="form-label">Status</label>
                        <select class="form-select" id="levelStatus" name="levelStatus" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Level</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Level Modal -->
<div class="modal fade" id="EditLevelModal" tabindex="-1" aria-labelledby="EditLevelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="EditLevelModalLabel">Edit Level</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editLevelForm">
                    <input type="hidden" id="editLevelId" name="levelId">
                    <div class="mb-3">
                        <label for="editLevelName" class="form-label">Level Name</label>
                        <input type="text" class="form-control" id="editLevelName" name="levelName" required>
                    </div>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Deactivate Level Modal -->
<div class="modal fade" id="DeactivateLevelModal" tabindex="-1" aria-labelledby="DeactivateLevelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="DeactivateLevelModalLabel">Deactivate Level</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="deactivateLevelId" name="level_id">
                <p>Are you sure you want to deactivate this level?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="confirmDeactivate">Deactivate</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Archives Modal -->
<div class="modal fade" id="ArchivesModal" tabindex="-1" aria-labelledby="ArchivesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ArchivesModalLabel">Archives</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Archives content -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="background.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "800",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    $(document).ready(function() {
        $('#addLevelForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'logics/level_management_logic.php',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        toastr.success(response.message);
                        $('#AddLevelModal').modal('hide');
                        setTimeout(function() {
                            location.reload(); // Reload the page after 0.8 seconds
                        }, 800);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred while adding the level');
                }
            });
        });

        $('#EditLevelModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var levelId = button.data('id');
            var levelName = button.data('name');
            var levelStatus = button.data('status');

            var modal = $(this);
            modal.find('#editLevelId').val(levelId);
            modal.find('#editLevelName').val(levelName);
            modal.find('#editLevelStatus').val(levelStatus);
        });

        $('#editLevelForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'logics/edit_level_logic.php',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        toastr.success(response.message);
                        $('#EditLevelModal').modal('hide');
                        setTimeout(function() {
                            location.reload(); // Reload the page after 0.8 seconds
                        }, 800);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred while editing the level');
                }
            });
        });

        $('#DeactivateLevelModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var levelId = button.data('id');

            var modal = $(this);
            modal.find('#deactivateLevelId').val(levelId);
        });

        $('#confirmDeactivate').on('click', function() {
            var levelId = $('#deactivateLevelId').val();
            $.ajax({
                type: 'POST',
                url: 'logics/deactivate_level_logic.php',
                data: { level_id: levelId },
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        toastr.success(response.message);
                        $('#DeactivateLevelModal').modal('hide');
                        setTimeout(function() {
                            location.reload(); // Reload the page after 0.8 seconds
                        }, 800);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred while deactivating the level');
                }
            });
        });
    });
</script>
</body>
</html>
