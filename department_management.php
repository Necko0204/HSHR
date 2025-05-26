<?php
session_name('admin_session');
session_start();
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
        <h2 class="fw-bold mb-0"><i class="fas fa-building"></i> Department Management</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#levelManagementModal">
            <i class="fas fa-cogs"></i> Manage Level
        </button>
    </div>

    
</main>
<!-- Unified Modal -->
<div class="modal fade" id="levelManagementModal" tabindex="-1" aria-labelledby="levelManagementModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Level Management</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       
        <ul class="nav nav-tabs" id="levelTabs">
          <li class="nav-item">
          <a class="nav-link active" data-bs-toggle="tab" href="#activeLevels">Active Levels</a>

          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#archives">Archives</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#addLevel">Add Level</a>
          </li>
        </ul>

       
        <div class="tab-content mt-3">
          
          <div class="tab-pane fade show active" id="activeLevels">
            <div class="table-responsive" id="activeLevelsTable">
              <!-- Active levels will be loaded here -->
            </div>
          </div>

        
          <div class="tab-pane fade" id="archives">
            <div class="table-responsive" id="inactiveLevelsTable">
              <!-- Inactive levels will be loaded here -->
            </div>
          </div>

        
          <div class="tab-pane fade" id="addLevel">
            <form id="addLevelForm">
              <div class="mb-3">
                <label for="levelName" class="form-label">Level Name</label>
                <input type="text" class="form-control" id="levelName" name="levelName" required>
              </div>
              <div class="mb-3">
                <label for="levelDescription" class="form-label">Level Description</label>
                <textarea class="form-control" id="levelDescription" name="levelDescription" rows="3"></textarea>
              </div>
                <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Level
                </button>
            </form>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
        <i class="fa fa-times"></i> Close
      </button>
      </div>
    </div>
  </div>
</div>


 <!-- Edit Role Modal -->
 <!-- <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
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
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div> -->

<!-- Deactivate Role Confirmation Modal -->
<!-- <div class="modal fade" id="deactivateRoleModal" tabindex="-1" aria-labelledby="deactivateRoleModalLabel" aria-hidden="true">
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
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeactivate">Deactivate</button>
      </div>
    </div>
  </div>
</div> -->

<!-- Reactivate Role Confirmation Modal -->
<!-- <div class="modal fade" id="reactivateRoleModal" tabindex="-1" aria-labelledby="reactivateRoleModalLabel" aria-hidden="true">
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
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" id="confirmReactivate">Restore</button>
      </div>
    </div>
  </div>
</div> -->

    <!-- Bootstrap JS -->
    <script src="background.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
    $(document).ready(function() {
        // Load Active Levels when the modal opens
        $('#levelManagementModal').on('shown.bs.modal', function() {
            $('#activeLevelsTable').load('includes/fetch_active_levels.php');
        });

        // Load Inactive Levels when switching to Archives tab
        $('#levelTabs a[href="#archives"]').on('shown.bs.tab', function() {
            $('#inactiveLevelsTable').load('includes/fetch_inactive_levels.php');
        });

        // Add Level Form Submission
        $('#addLevelForm').submit(function(e) {
            e.preventDefault();
            $.post('logics/level_logic.php', $(this).serialize(), function(response) {
                    toastr.clear();
                    toastr.success('Level added successfully!');
                    $('#addLevelForm')[0].reset();
            
                    // Reload active levels after adding a new one
                    $('#activeLevelsTable').load('includes/fetch_active_levels.php');
            }).fail(function() {
                    toastr.clear();
                    toastr.error('Failed to add level. Please try again.');
            });
        });
    });
    </script>
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
</script>


<!-- <script>
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

    // Handle form submission for role edits
    $('#editRoleForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'logics/role_logic.php', // Assure this handles edits
            data: $(this).serialize(),
            success: function(response) {
                toastr.clear();
                toastr.success('Role updated successfully!');
                $('#editRoleModal').modal('hide');
                setTimeout(function() {
                    location.reload(); 
                }, 800);
            },
            error: function() {
                toastr.clear(); 
                toastr.error('Failed to update role. Please try again.');
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
</script> -->
</body>
</html>