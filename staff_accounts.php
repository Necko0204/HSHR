<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');

require_once __DIR__ . '/includes/admin_page.php';
include 'includes/breadcrumb.php';
include 'db_config.php';
include 'helper.php';

// Redirect if the admin is not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}
$employees = getEmployees(); // Fetch employees from the database
$staffAccounts = getStaffAccounts($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/asdasdasd123123123123123.jpg">
    <title>Holy Spirit Human Resource</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="background.css">
</head>
<body>

<!-- Sidebar & Navbar -->
<div class="main-container">
    <?php include 'sidebar.php'; ?>
</div>
<div class="content-container">
    <?php include 'nav_header.php'; ?>
</div>

<!-- Main Content -->
<main class="wrapper">
                <?php
                echo generateBreadcrumb();
                ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="fa fa-id-card"></i> Employee Accounts</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAccountModal">
            <i class="fas fa-user-plus"></i> Create Account
        </button>
    </div>

<div class="card shadow-lg border-1 rounded-3">
    <div class="card-header bg-gradient-primary text-black d-flex justify-content-between align-items-center">
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
        <div style="max-height: 530px; overflow-y: auto;">
        <table class="table table-borderless table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="display:none;">ID</th>
                    <th style="min-width: 150px; width: 150px;">Profile Picture</th>
                    <th style="min-width: 150px; width: 150px;">Username</th>
                    <th style="min-width: 150px; width: 150px;">Role</th>
                    <th style="min-width: 150px; width: 150px;">Status</th>
                    <th style="min-width: 150px; width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($staffAccounts)): ?>
                    <?php foreach ($staffAccounts as $staff): ?>
                        <tr>
                            <td style="display:none;"><?= htmlspecialchars($staff['id']); ?></td>
                            <td style="width: 150px;">
                                <?php if (!empty($staff['profile_picture'])): ?>
                                    <img src="<?= htmlspecialchars($staff['profile_picture']); ?>" alt="Profile" class="img-thumbnail" class="img-thumbnail" style="width: 125px; height: 125px;">
                                <?php else: ?>
                                    <span>No Image</span>
                                <?php endif; ?>
                            </td>
                            <td style="width: 150px;"><?= htmlspecialchars($staff['username']); ?></td>
                            <td style="width: 150px;"><?= htmlspecialchars($staff['role']); ?></td>
                            <td style="width: 150px;">
                                <span class="badge bg-success">
                                    <?= htmlspecialchars($staff['status']); ?>
                                </span>
                            </td>
                            <td style="min-width: 150px; width: 150px;">
                                <button class="btn btn-warning btn-sm edit-btn"
                                    data-id="<?= htmlspecialchars((string) $staff['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                    data-employee_id="<?= htmlspecialchars((string) $staff['employee_id'], ENT_QUOTES, 'UTF-8'); ?>"
                                    data-username="<?= htmlspecialchars((string) $staff['username'], ENT_QUOTES, 'UTF-8'); ?>"
                                    data-role="<?= htmlspecialchars((string) $staff['role'], ENT_QUOTES, 'UTF-8'); ?>"
                                    data-status="<?= htmlspecialchars((string) $staff['status'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>

                                <button class="btn toggle-status-btn btn-sm <?= strtolower((string) $staff['status']) === 'active' ? 'btn-danger' : 'btn-success' ?>"
                                        data-id="<?= htmlspecialchars((string) $staff['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-status="<?= strtolower((string) $staff['status']) === 'active' ? 'Deactivate' : 'Reactivate' ?>">
                                    <i class="fas <?= strtolower((string) $staff['status']) === 'active' ? 'fa-times' : 'fa-check' ?>"></i>
                                    <?= strtolower((string) $staff['status']) === 'active' ? 'Deactivate' : 'Reactivate' ?>
                                </button>


                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No staff accounts found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
<!-- Create Account Modal -->
<div class="modal fade" id="createAccountModal" tabindex="-1" aria-labelledby="createAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Make the modal wider -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAccountModalLabel">Create Employee Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="staff_create_acc.php" id="createAccountForm">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="employeeSelect" class="form-label">Select Employee</label>
                            <select class="form-select" id="employeeSelect" name="employee_id" required>
                                <option value="" disabled selected>Choose an Employee</option>
                                <?php foreach ($employees as $employee): ?>
                                    <?php if (!in_array($employee['id'], array_column($staffAccounts, 'employee_id'))): ?>
                                        <option value="<?= htmlspecialchars((string) $employee['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <?= htmlspecialchars($employee['lastname'] . ', ' . $employee['firstname']); ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                    </div>

                    <div class="row mt-3">
                    <div class="col-md-6 position-relative">
                        <label for="password" class="form-label">Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control pe-5" id="password" name="password" required>
                            <span class="position-absolute top-50 end-0 translate-middle-y me-3" id="togglePassword" style="cursor: pointer;">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="" disabled selected>Choose a Role</option>
                            <option value="staff">Staff</option>
                            <option value="intern">Intern</option>
                        </select>
                    </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="profilePicture" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" id="profilePicture" name="profile_picture" accept="image/*">
                        </div>
                        <div class="col-md-6 text-center">
                            <img id="profilePicturePreview" src="#" alt="Profile Picture Preview" class="img-thumbnail" style="display: none; width: 125px; height: 125px; object-fit: cover;">
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="d-flex justify-content-center gap-2 mt-3">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-save"></i> SAVE
                            </button>
                            <button type="button" class="btn btn-outline-danger px-4" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i> CANCEL
                            </button>
                        </div>

                    </div>
                </form>
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
                <div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
                    <?php
                    include 'db_config.php';

                    // Fetch only inactive staff accounts
                    $sql = "SELECT id, employee_id, username, role, profile_picture, status FROM staff_accounts WHERE status = 'Inactive'";
                    $result = $conn->query($sql);
                    ?>

                    <div style="max-height: 680px; overflow-y: auto;">
                        <?php if ($result->num_rows > 0): ?>
                            <table class="table table-borderless table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="display:none;">ID</th>
                                        <th style="min-width: 150px;">Profile Picture</th>
                                        <th style="min-width: 150px;">Employee ID</th>
                                        <th style="min-width: 150px;">Username</th>
                                        <th style="min-width: 150px;">Role</th>
                                        <th style="min-width: 150px;">Status</th>
                                        <th style="min-width: 150px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($staff = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td style="display:none;"><?= htmlspecialchars($staff['id']); ?></td>
                                            <td>
                                                <?php if (!empty($staff['profile_picture'])): ?>
                                                    <img src="<?= htmlspecialchars($staff['profile_picture']); ?>" alt="Profile" class="img-thumbnail" width="100" height="100" style="object-fit: cover;">
                                                <?php else: ?>
                                                    <span>No Image</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($staff['employee_id']); ?></td>
                                            <td><?= htmlspecialchars($staff['username']); ?></td>
                                            <td><?= htmlspecialchars($staff['role']); ?></td>
                                            <td>
                                                <span class="badge bg-danger">
                                                    <?= htmlspecialchars($staff['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn toggle-status-btn btn-sm btn-success"
                                                    data-id="<?= htmlspecialchars((string) $staff['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    data-status="Reactivate">
                                                    <i class="fas fa-check"></i> Reactivate
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="text-center py-4">
                            <h5 class="text-muted">No Inactive staff accounts found.</h5>
                        </div>
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
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Employee Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="edit_id" name="id">

                    <div class="mb-3">
                        <label for="edit_username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="edit_username" name="username">
                    </div>

                    <div class="mb-3">
                        <label for="edit_role" class="form-label">Role</label>
                        <select class="form-control" id="edit_role" name="role">
                            <option value="staff">Staff</option>
                            <option value="intern">Intern</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Deactivate User Confirmation Modal -->
<div class="modal fade" id="deactivateModal" tabindex="-1" aria-labelledby="deactivateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deactivateModalLabel">Confirm Status Change</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to change the status of this user?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="confirmDeactivateBtn">
            <i class="fas fa-check"></i> Deactivate
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times"></i> Cancel
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap & Custom JS -->
<script src="background.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let userIdToDeactivate = null;
        let currentStatus = null;


        // Password toggle functionality
        document.getElementById('togglePassword').addEventListener('click', function () {
            let passwordInput = document.getElementById('password');
            let icon = this.querySelector('i');
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            }
        });

        // Profile picture preview functionality
        document.getElementById('profilePicture').addEventListener('change', function (event) {
            let preview = document.getElementById('profilePicturePreview');
            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = "block";
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = "none";
            }
        });

        // Toastr configuration for smooth alerts
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "800", // Reduced from 1000
            "extendedTimeOut": "800", // Reduced from 1000
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Handle edit button click
        document.querySelectorAll(".edit-btn").forEach(button => {
            button.addEventListener("click", function () {
                document.getElementById("edit_id").value = this.getAttribute("data-id");
                document.getElementById("edit_username").value = this.getAttribute("data-username");
                document.getElementById("edit_role").value = this.getAttribute("data-role");

                let editModal = new bootstrap.Modal(document.getElementById("editModal"));
                editModal.show();
            });
        });

   // Handle toggle status button clicks
        document.addEventListener("click", function (event) {
            if (event.target.closest(".toggle-status-btn")) {
                let button = event.target.closest(".toggle-status-btn");
                userIdToDeactivate = button.getAttribute("data-id");
                currentStatus = button.getAttribute("data-status");

                let modalBody = document.querySelector(".modal-body");
                let confirmButton = document.getElementById("confirmDeactivateBtn");

                if (currentStatus === "Deactivate") {
                    modalBody.textContent = "Are you sure you want to deactivate this user?";
                    confirmButton.innerHTML = '<i class="fas fa-ban"></i> Deactivate';
                } else {
                    modalBody.textContent = "Are you sure you want to reactivate this user?";
                    confirmButton.innerHTML = '<i class="fas fa-check"></i> Reactivate';
                }


                // Close the Archives Modal if it is open
                let archivesModal = bootstrap.Modal.getInstance(document.getElementById("ArchivesModal"));
                if (archivesModal) {
                    archivesModal.hide();
                }

                // Open the deactivation modal after a short delay
                setTimeout(() => {
                    let deactivateModal = new bootstrap.Modal(document.getElementById("deactivateModal"));
                    deactivateModal.show();
                }, 300);
            }
        });

        // Handle confirm button click
        document.getElementById("confirmDeactivateBtn").addEventListener("click", function () {
            if (userIdToDeactivate) {
                let action = currentStatus === "Deactivate" ? "deactivate" : "reactivate";

                // Close modal
                let modal = bootstrap.Modal.getInstance(document.getElementById("deactivateModal"));
                if (modal) modal.hide();

                // Send request
                fetch("logics/staffacc_updatestatus.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `id=${userIdToDeactivate}&action=${action}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success(`User ${action}d successfully!`);

                        // Update button and status badge
                        let button = document.querySelector(`.toggle-status-btn[data-id='${userIdToDeactivate}']`);
                        let row = button.closest("tr");
                        let badge = row.querySelector(".badge");

                        if (action === "deactivate") {
                            button.innerHTML = `<i class="fas fa-check"></i> Reactivate`;
                            button.classList.remove("btn-danger");
                            button.classList.add("btn-success");
                            button.setAttribute("data-status", "Reactivate");
                            badge.textContent = "Inactive";
                            badge.classList.remove("bg-success");
                            badge.classList.add("bg-danger");
                        } else {
                            button.innerHTML = `<i class="fas fa-times"></i> Deactivate`;
                            button.classList.remove("btn-success");
                            button.classList.add("btn-danger");
                            button.setAttribute("data-status", "Deactivate");
                            badge.textContent = "Active";
                            badge.classList.remove("bg-danger");
                            badge.classList.add("bg-success");
                        }

                        setTimeout(() => location.reload(), 800);
                    } else {
                        toastr.error("Error updating user status.");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    toastr.error("An unexpected error occurred.");
                });
            }
        });

        // Handle edit form submission
        document.getElementById("editForm").addEventListener("submit", function (event) {
            event.preventDefault();

            let formData = new FormData(this);
            fetch("logics/staffacc_updateacc.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success("Employee details updated successfully!");
                    setTimeout(() => location.reload(), 800); // Reduced from 1000
                } else {
                    toastr.error("Error updating staff details");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                toastr.error("An unexpected error occurred.");
            });
        });

        // Handle create account form submission
        document.getElementById("createAccountForm").addEventListener("submit", function(event) {
            event.preventDefault();

            let formData = new FormData(this);

            fetch("staff_create_acc.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    toastr.success("Employee account created successfully!");
                    setTimeout(() => location.reload(), 800); // Reduced from 1000
                } else {
                    toastr.error(data.message || "Error creating employee account.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                toastr.error("An unexpected error occurred.");
            });
        });
    });
</script>


</body>
</html>
