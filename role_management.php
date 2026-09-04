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
        <?php echo generateBreadcrumb(); ?>

        <!-- Title Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0"><i class="fas fa-user-shield"></i> Role Management</h2>
            <!-- Add Role Button -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                <i class="fas fa-plus"></i> Add Role
            </button>
        </div>

<!-- Role Management Table -->
<div class="card shadow-lg border-1 rounded-3">
    <div class="card-header bg-gradient-primary text-black d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <input type="text" id="searchInput" class="form-control" placeholder="Search..." style="width: 150px;">
        </div>
        <button type="button" class="btn btn-secondary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#ArchivesModal">
            <i class="fa fa-folder"></i> <span>Archives</span>
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive" style="max-height: 530px; overflow-y: auto;">
            <?php
            $sql = "SELECT * FROM roles WHERE status = 'Active' ORDER BY role_id ASC";
            $result = $conn->query($sql);
            ?>
            <table class="table table-borderless table-hover table-fixed align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 150px; display: none;">Role ID</th>
                        <th style="min-width: 180px; max-width: 200px;">Role Name</th>
                        <th style="min-width: 300px; max-width: 400px;">Description</th>
                        <th style="min-width: 120px; max-width: 150px;">Status</th>
                        <th style="min-width: 200px; max-width: 250px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="rolesTable">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td style="display: none;"><?php echo htmlspecialchars($row['role_id']); ?></td>
                            <td style="min-width: 180px; max-width: 200px;">
                                <?php echo htmlspecialchars(str_replace('_', ' ', $row['role_name'])); ?>
                            </td>
                            <td style="min-width: 300px; max-width: 400px; word-break: break-word; white-space: normal;">
                                <?php echo htmlspecialchars($row['description']); ?>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge <?= $row['status'] == 'Active' ? 'bg-success' : 'bg-danger' ?>">
                                    <?= htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                            <td style="min-width: 200px; max-width: 250px;">
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editRoleModal" data-role-id="<?= htmlspecialchars($row['role_id']); ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deactivateRole('<?= htmlspecialchars($row['role_id']); ?>')">
                                    <i class="fas fa-trash-alt"></i> Deactivate
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php $conn->close(); ?>
        </div>
    </div>
</div>

</main>

    <!-- Archives Modal -->
    <div class="modal fade" id="ArchivesModal" tabindex="-1" aria-labelledby="ArchivesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ArchivesModalLabel">Archives</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive" style="overflow-x: auto; white-space: nowrap; max-height: 530px; overflow-y: auto;">
                    <?php
                    include 'db_config.php';
                    $sql = "SELECT * FROM roles WHERE status = 'Inactive' ORDER BY role_id ASC";
                    $result = $conn->query($sql);
                    ?>
                    <div class="table-responsive" style="overflow-x: auto; white-space: nowrap; max-height: 530px; overflow-y: auto;">
                        <?php if ($result->num_rows > 0): ?>
                            <table class="table table-borderless table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 150px; display: none;">Role ID</th>
                                        <th style="width: 150px;">Role Name</th>
                                        <th style="width: 150px;">Description</th>
                                        <th style="width: 150px;">Status</th>
                                        <th style="width: 150px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="inactiveRolesTable">
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td style="display: none;"><?php echo htmlspecialchars($row['role_id']); ?></td>
                                            <td><?php echo htmlspecialchars($row['role_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                                            <td>
                                                <span class="badge bg-danger">Inactive</span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm" onclick="activateRole('<?php echo htmlspecialchars($row['role_id']); ?>')">
                                                    <i class="fas fa-undo"></i> Restore
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="text-center py-4">
                            <h5 class="text-muted">No inactive role found</div>
                        <?php endif; ?>
                    </div>
                    <?php $conn->close(); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>   </button></td></tr>


    <!-- Add Role Modal -->
    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoleModalLabel">Add New Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addRoleForm">
                        <div class="mb-3">
                            <label for="roleName" class="form-label">Role Name</label>
                            <input type="text" class="form-control" id="roleName" name="roleName" required>
                        </div>
                        <div class="mb-3">
                            <label for="roleDescription" class="form-label">Role Description</label>
                            <textarea class="form-control" id="roleDescription" name="roleDescription" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Role
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editRoleForm">
          <input type="hidden" id="editRoleId" name="role_id">
          <div class="mb-3">
            <label for="editRoleName" class="form-label">Role Name</label>
            <input type="text" class="form-control" id="editRoleName" name="roleName" required>
          </div>
          <div class="mb-3">
            <label for="editRoleDescription" class="form-label">Role Description</label>
            <textarea class="form-control" id="editRoleDescription" name="roleDescription" rows="3"></textarea>
          </div>
          <button type="submit" class="btn btn-success">
          <i class="fa fa-check"></i> Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Deactivate Role Confirmation Modal -->
<div class="modal fade" id="deactivateRoleModal" tabindex="-1" aria-labelledby="deactivateRoleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deactivateRoleModalLabel">Confirm Deactivation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to deactivate this role?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="confirmDeactivate">
        <i class="fa fa-check"></i> Deactivate
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fa fa-times"></i> Cancel
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Reactivate Role Confirmation Modal -->
<div class="modal fade" id="reactivateRoleModal" tabindex="-1" aria-labelledby="reactivateRoleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reactivateRoleModalLabel">Confirm Reactivation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to restore this role?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="confirmReactivate">
        <i class="fa fa-times"></i> Restore</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="fa fa-check"></i> Cancel</button>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "1000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    $(document).ready(function() {
    $('#addRoleForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: 'logics/role_logic.php', // Ensure this points to the correct PHP file handling the insertion
            data: $(this).serialize(),
            success: function(response) {
                toastr.clear(); // Clear the loading indicator
                toastr.success('Role added successfully!');
                $('#addRoleModal').modal('hide');
                $('#addRoleForm')[0].reset();

                // Reload the page after 0.8 seconds
                setTimeout(function() {
                    location.reload();
                }, 800);
            },
            error: function() {
                toastr.clear(); // Clear the loading indicator
                toastr.error('Failed to add role. Please try again.');
            }
        });
    });
});
    </script>

<script>
    // Open edit modal with role details
    document.querySelectorAll('[data-bs-target="#editRoleModal"]').forEach(button => {
        button.addEventListener('click', event => {
            const roleId = button.getAttribute('data-role-id');

            // Fetch and populate data for the role
            // Add AJAX request here to fetch role data by ID if needed

            document.getElementById('editRoleId').value = roleId;
            document.getElementById('editRoleName').value = button.closest('tr').querySelector('td:nth-child(2)').innerText;
            document.getElementById('editRoleDescription').value = button.closest('tr').querySelector('td:nth-child(3)').innerText;
        });
    });

    $('#editRoleForm').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        type: 'POST',
        url: 'logics/role_edit_logic.php',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            toastr.clear();

            if (response.status === 'success') {
                toastr.success(response.message);
                $('#editRoleModal').modal('hide');
                setTimeout(() => location.reload(), 800);
            } else if (response.status === 'info') {
                toastr.info(response.message);
            } else {
                toastr.error(response.message || 'Something went wrong.');
            }
        },
        error: function(xhr, status, error) {
            toastr.clear();
            toastr.error('Failed to update role. Please try again.');
            console.error('AJAX Error:', status, error);
        }
    });
});

    // Deactivate role confirmation
    function deactivateRole(roleId) {
        $('#deactivateRoleModal').modal('show');

        document.getElementById('confirmDeactivate').onclick = function() {
            // Replace with your AJAX call to deactivate the role passing roleId
            $.ajax({
                type: 'POST',
                url: 'logics/role_deactivate.php', // Assuming this handles the deactivation
                data: { role_id: roleId },
                success: function(response) {
                    toastr.success('Role deactivated successfully!');
                    $('#deactivateRoleModal').modal('hide');
                    setTimeout(function() {
                        location.reload();
                    }, 800);
                },
               error: function() {
                    toastr.error('Failed to deactivate role. Please try again.');
                }
            });
        };
    }

    function activateRole(roleId) {
    // Check if Archives Modal is visible before closing it
    if ($('#ArchivesModal').hasClass('show')) {
        $('#ArchivesModal').modal('hide');

        // Delay to ensure Archives Modal fully hides before opening the Reactivate Modal
        setTimeout(function () {
            $('#reactivateRoleModal').modal('show');
        }, 300);
    } else {
        $('#reactivateRoleModal').modal('show');
    }

    // Set the role ID for confirmation button
    document.getElementById('confirmReactivate').onclick = function() {
        $.ajax({
            type: 'POST',
            url: 'logics/role_reactivate.php', // Adjust to your PHP script
            data: { role_id: roleId },
            success: function(response) {
                toastr.success('Role reactivated successfully!');
                $('#reactivateRoleModal').modal('hide');
                setTimeout(function() {
                    location.reload();
                }, 800);
            },
            error: function() {
                toastr.error('Failed to reactivate role. Please try again.');
            }
        });
    };
}


</script>
</body>
</html>
