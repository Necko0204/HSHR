<?php
require_once __DIR__ . '/includes/admin_page.php';
include 'includes/breadcrumb.php';
include 'db_config.php';
include 'helper.php';

$employees = getEmployees(); // Fetch employees from the database

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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

<!-- Main Content Wrapper -->
<main class="wrapper">
                <?php
                echo generateBreadcrumb();
                ?>
    <div class="d-flex justify-content-start align-items-center mb-4">
        <h2 class="fw-bold mb-0"> <i class="fas fa-calendar-alt me-2"></i>Employee Leave Request</h2>
    </div>

        <!-- Employee Leave Request Table -->
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
                require 'db_config.php';

                $sql = "SELECT l.leave_id, l.employee_id, e.firstname, e.lastname, lt.leave_name,
                                l.leave_start_date, l.leave_end_date, l.total_days, l.status, l.request_date
                            FROM leave_requests l
                            JOIN employees e ON l.employee_id = e.id
                            JOIN leave_types lt ON l.leave_type_id = lt.leave_type_id
                            WHERE l.status = 'Pending'
                            ORDER BY l.leave_id";
                $result = $conn->query($sql);
                ?>
                <div style="max-height: 530px; overflow-y: auto;">
                    <?php if ($result->num_rows > 0): ?>
                        <table class="table table-borderless table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="hidden-id" style="min-width: 100px;">Leave ID</th>
                                    <th style="min-width: 150px;">Employee Name</th>
                                    <th style="min-width: 150px;">Leave Type</th>
                                    <th style="min-width: 150px;">Start Date</th>
                                    <th style="min-width: 150px;">End Date</th>
                                    <th style="min-width: 150px;">Total Days</th>
                                    <th style="min-width: 150px;">Request Date</th>
                                    <th style="min-width: 150px;">Status</th>
                                    <th style="min-width: 150px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="leaveRequestsTable">
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr style="min-width: 150px;">
                                        <td class="hidden-id"><?= htmlspecialchars($row["leave_id"]) ?></td>
                                        <td><?= htmlspecialchars($row["firstname"] . ' ' . $row["lastname"]) ?></td>
                                        <td><?= htmlspecialchars($row["leave_name"]) ?></td>
                                        <td><?= htmlspecialchars($row["leave_start_date"]) ?></td>
                                        <td><?= htmlspecialchars($row["leave_end_date"]) ?></td>
                                        <td><?= htmlspecialchars($row["total_days"]) ?></td>
                                        <td><?= htmlspecialchars($row["request_date"]) ?></td>
                                        <td>
                                            <span class="badge bg-warning text-white"><?= htmlspecialchars($row["status"]) ?></span>
                                        </td>
                                        <td>
                                            <button class="btn btn-success btn-sm me-2" onclick="showModal('approve', <?= htmlspecialchars($row['leave_id']) ?>)">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick="showModal('reject', <?= htmlspecialchars($row['leave_id']) ?>)">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">No Pending Leave Request</div>
                    <?php endif; ?>
                </div>
                <?php $conn->close(); ?>
            </div>
        </div>
    </div>
</main>
<!-- Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="modalMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmAction">
                    <i class="fas fa-check"></i> Confirm
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
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
                <!-- Tabs for switching between Approved & Rejected -->
                <ul class="nav nav-pills mb-3 custom-tabs justify-content-center border-bottom pb-2" id="archivesTabs" role="tablist">
                    <li class="nav-item mx-3" role="presentation">
                        <button class="nav-link active" id="approved-tab" data-bs-toggle="pill" data-bs-target="#approved-content" type="button" role="tab">
                            <i class="fas fa-check-circle"></i> Approved
                        </button>
                    </li>
                    <li class="nav-item mx-3" role="presentation">
                        <button class="nav-link" id="rejected-tab" data-bs-toggle="pill" data-bs-target="#rejected-content" type="button" role="tab">
                            <i class="fas fa-times-circle"></i> Rejected
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Approved Requests Table -->
                    <div class="tab-pane fade show active" id="approved-content" role="tabpanel">
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <?php
                            require 'db_config.php'; // Ensure your database connection is included

                            $sql2 = "SELECT l.leave_id, l.employee_id, e.firstname, e.lastname, lt.leave_name,
                                    l.leave_start_date, l.leave_end_date, l.total_days, l.status, l.request_date
                                    FROM leave_requests l
                                    JOIN employees e ON l.employee_id = e.id
                                    JOIN leave_types lt ON l.leave_type_id = lt.leave_type_id
                                    WHERE l.status = 'Approved'";
                            $result2 = $conn->query($sql2);

                            if ($result2 && $result2->num_rows > 0): ?>
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="hidden-id">Leave ID</th>
                                            <th style="min-width: 150px;">Employee Name</th>
                                            <th style="min-width: 150px;">Leave Type</th>
                                            <th style="min-width: 150px;">Start Date</th>
                                            <th style="min-width: 150px;">End Date</th>
                                            <th style="min-width: 150px;">Total Days</th>
                                            <th style="min-width: 150px;">Request Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row2 = $result2->fetch_assoc()): ?>
                                            <tr>
                                                <td class="hidden-id"><?= htmlspecialchars($row2["leave_id"]) ?></td>
                                                <td><?= htmlspecialchars($row2["firstname"] . ' ' . $row2["lastname"]) ?></td>
                                                <td><?= htmlspecialchars($row2["leave_name"]) ?></td>
                                                <td><?= htmlspecialchars($row2["leave_start_date"]) ?></td>
                                                <td><?= htmlspecialchars($row2["leave_end_date"]) ?></td>
                                                <td><?= htmlspecialchars($row2["total_days"]) ?></td>
                                                <td><?= htmlspecialchars($row2["request_date"]) ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p class="text-center">No approved leave requests found.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Rejected Requests Table -->
                    <div class="tab-pane fade" id="rejected-content" role="tabpanel">
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <?php
                            $sql3 = "SELECT l.leave_id, l.employee_id, e.firstname, e.lastname, lt.leave_name,
                                    l.leave_start_date, l.leave_end_date, l.total_days, l.status, l.request_date
                                    FROM leave_requests l
                                    JOIN employees e ON l.employee_id = e.id
                                    JOIN leave_types lt ON l.leave_type_id = lt.leave_type_id
                                    WHERE l.status = 'Rejected'";
                            $result3 = $conn->query($sql3);

                            if ($result3 && $result3->num_rows > 0): ?>
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="hidden-id">Leave ID</th>
                                            <th style="min-width: 150px;">Employee Name</th>
                                            <th style="min-width: 150px;">Leave Type</th>
                                            <th style="min-width: 150px;">Start Date</th>
                                            <th style="min-width: 150px;">End Date</th>
                                            <th style="min-width: 150px;">Total Days</th>
                                            <th style="min-width: 150px;">Request Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row3 = $result3->fetch_assoc()): ?>
                                            <tr>
                                                <td class="hidden-id"><?= htmlspecialchars($row3["leave_id"]) ?></td>
                                                <td><?= htmlspecialchars($row3["firstname"] . ' ' . $row3["lastname"]) ?></td>
                                                <td><?= htmlspecialchars($row3["leave_name"]) ?></td>
                                                <td><?= htmlspecialchars($row3["leave_start_date"]) ?></td>
                                                <td><?= htmlspecialchars($row3["leave_end_date"]) ?></td>
                                                <td><?= htmlspecialchars($row3["total_days"]) ?></td>
                                                <td><?= htmlspecialchars($row3["request_date"]) ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p class="text-center">No rejected leave requests found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div> <!-- End of tab-content -->
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
<!-- Bootstrap CSS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- jQuery (Required for Toastr) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
// Toastr configuration for smooth alerts
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
    let currentAction = '';
    let currentLeaveId = '';

    function showModal(action, leaveId) {
        currentAction = action;
        currentLeaveId = leaveId;

        const message = action === 'approve' ? 'Are you sure you want to approve this request?' : 'Are you sure you want to reject this request?';
        document.getElementById('modalMessage').textContent = message;
        new bootstrap.Modal(document.getElementById('confirmationModal')).show();
    }

    document.getElementById('confirmAction').addEventListener('click', function() {
        $.ajax({
            url: 'logics/update_leave_status.php',
            type: 'POST',
            data: { action: currentAction, leave_id: currentLeaveId },
            success: function(response) {
                const res = typeof response === 'string' ? JSON.parse(response) : response;
                if (res.success) {
                    toastr.success(res.message);
                } else {
                    toastr.error(res.message);
                }
                setTimeout(function() { location.reload(); }, 800);
            },
            error: function() {
                toastr.error('An error occurred');
            }
        });


        bootstrap.Modal.getInstance(document.getElementById('confirmationModal')).hide();
    });

document.getElementById("searchInput").addEventListener("keyup", function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#leaveRequestsTable tr");

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});
</script>
</body>
</html>
