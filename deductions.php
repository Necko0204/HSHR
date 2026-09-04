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

    <!-- Main Content Wrapper -->
    <main class="wrapper">
                <?php
                echo generateBreadcrumb();
                ?>
        <div class="d-flex justify-content-start align-items-center mb-3">
            <h2 class="fw-bold mb-0">
                <i class="fa fa-list-alt"></i> Deductions List
            </h2>
            <button class="btn btn-primary ms-auto d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addDeductionsModal">
                <i class="fa fa-plus"></i> Add Deductions
            </button>
        </div>

        <!-- Deductions Table -->
        <div class="card shadow-lg border-1 rounded-3 mb-4" id="deductionsCard">
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
            <div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
                <?php
                $sql = "SELECT id, name, description, deduction_type, amount, percentage, max_cap, is_mandatory, status
                        FROM deductions
                        WHERE status = 'Active'";
                $result = $conn->query($sql);
                ?>
                <div style="max-height: 530px; overflow-y: auto;">
                    <table class="table table-borderless table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="hidden-id">ID</th>
                                <th style="min-width: 150px;">Name</th>
                                <th style="min-width: 150px;">Description</th>
                                <th style="min-width: 150px;">Deduction Type</th>
                                <th style="min-width: 150px;">Amount</th>
                                <th style="min-width: 150px;">Percentage</th>
                                <th style="min-width: 150px;">Max Cap</th>
                                <th style="min-width: 150px;">Is Mandatory</th>
                                <th style="min-width: 150px;">Status</th>
                                <th style="min-width: 150px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="deductionsTable">
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="hidden-id"><?= htmlspecialchars($row["id"]) ?></td>
                                    <td><?= htmlspecialchars($row["name"]) ?></td>
                                    <td><?= htmlspecialchars($row["description"]) ?></td>
                                    <td><?= htmlspecialchars($row["deduction_type"]) ?></td>
                                    <td><?= htmlspecialchars($row["amount"]) ?></td>
                                    <td><?= htmlspecialchars($row["percentage"]) ?></td>
                                    <td><?= htmlspecialchars($row["max_cap"]) ?></td>
                                    <td><?= htmlspecialchars($row["is_mandatory"]) ?></td>
                                    <td>
                                        <?php if ($row["status"] == 'Active'): ?>
                                            <span class="badge bg-success"><?= htmlspecialchars($row["status"]) ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?= htmlspecialchars($row["status"]) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editDeductionModal" data-id="<?= htmlspecialchars($row["id"]) ?>">
                                            <i class="fa fa-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-danger btn-sm"
                                            data-id="<?= htmlspecialchars($row["id"]) ?>"
                                            onclick="deactivateDeduction(this)">
                                            <i class="fa fa-trash"></i> Deactivate
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <hr>

        <div class="d-flex justify-content-start align-items-center mb-3">
            <h2 class="fw-bold mb-0">
                <i class="fa fa-list-alt"></i> SSS Deductions
            </h2>
        </div>

        <div class="card shadow-lg border-1 rounded-3" id="sssDeductionsCard">
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
            <div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
                <?php
                $sql2 = "SELECT id, salary_range, salary_base, employee_share, employer_share, total_contribution, ec_contribution, other_contribution, total_with_others
                        FROM sss_deductions";
                $result2 = $conn->query($sql2);
                ?>
                <div style="max-height: 530px; overflow-y: auto;">
                    <table class="table table-borderless table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="hidden-id">ID</th>
                                <th style="min-width: 150px;">Salary Range</th>
                                <th style="min-width: 150px;">Salary Base</th>
                                <th style="min-width: 150px;">Employee Share</th>
                                <th style="min-width: 150px;">Employer Share</th>
                                <th style="min-width: 150px;">Total Contribution</th>
                                <th style="min-width: 150px;">EC Contribution</th>
                                <th style="min-width: 150px;">Other Contribution</th>
                                <th style="min-width: 150px;">Total with Others</th>
                            </tr>
                        </thead>
                        <tbody id="sssTable">
                            <?php while ($row2 = $result2->fetch_assoc()): ?>
                                <tr>
                                    <td class="hidden-id"><?= htmlspecialchars($row2["id"]) ?></td>
                                    <td><?= htmlspecialchars($row2["salary_range"]) ?></td>
                                    <td><?= htmlspecialchars($row2["salary_base"]) ?></td>
                                    <td><?= htmlspecialchars($row2["employee_share"]) ?></td>
                                    <td><?= htmlspecialchars($row2["employer_share"]) ?></td>
                                    <td><?= htmlspecialchars($row2["total_contribution"]) ?></td>
                                    <td><?= htmlspecialchars($row2["ec_contribution"]) ?></td>
                                    <td><?= htmlspecialchars($row2["other_contribution"]) ?></td>
                                    <td><?= htmlspecialchars($row2["total_with_others"]) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php $conn->close(); ?>

</main>


<!-- Add Deductions Modal -->
<div class="modal fade" id="addDeductionsModal" tabindex="-1" aria-labelledby="addDeductionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDeductionsModalLabel">Add Deduction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addDeductionForm">
                    <!-- Deduction Name -->
                    <div class="mb-3">
                        <label for="deductionName" class="form-label">Deduction Name</label>
                        <input type="text" class="form-control" id="deductionName" name="deductionName" required>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="deductionDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="deductionDescription" name="deductionDescription" rows="2"></textarea>
                    </div>

                    <!-- Deduction Type -->
                    <div class="mb-3">
                        <label for="deductionType" class="form-label">Deduction Type</label>
                        <select class="form-select" id="deductionType" name="deductionType" required>
                            <option value="" selected disabled>Select Type</option>
                            <option value="fixed">Fixed Amount</option>
                            <option value="percentage">Percentage</option>
                        </select>
                    </div>

                    <!-- Amount (Only for Fixed Type) -->
                    <div class="mb-3" id="amountField" style="display: none;">
                        <label for="deductionAmount" class="form-label">Amount (₱)</label>
                        <input type="number" step="0.01" class="form-control" id="deductionAmount" name="deductionAmount">
                    </div>

                    <!-- Percentage (Only for Percentage Type) -->
                    <div class="mb-3" id="percentageField" style="display: none;">
                        <label for="deductionPercentage" class="form-label">Percentage (%)</label>
                        <input type="number" step="0.01" class="form-control" id="deductionPercentage" name="deductionPercentage">
                    </div>

                    <!-- Max Cap (Only for Percentage Type) -->
                    <div class="mb-3" id="maxCapField" style="display: none;">
                        <label for="deductionMaxCap" class="form-label">Max Cap (₱)</label>
                        <input type="number" step="0.01" class="form-control" id="deductionMaxCap" name="deductionMaxCap">
                    </div>

                    <!-- Is Mandatory -->
                    <div class="mb-3">
                        <label class="form-label">Is Mandatory?</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="isMandatory" id="mandatoryYes" value="1" checked>
                            <label class="form-check-label" for="mandatoryYes">Yes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="isMandatory" id="mandatoryNo" value="0">
                            <label class="form-check-label" for="mandatoryNo">No</label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Add Deduction</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Edit Deduction Modal -->
<div class="modal fade" id="editDeductionModal" tabindex="-1" aria-labelledby="editDeductionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDeductionModalLabel">Edit Deduction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editDeductionForm">
                <div class="modal-body">
                    <input type="hidden" id="editDeductionId" name="id">
                    <div class="mb-3">
                        <label for="editName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editDescription" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editDeductionType" class="form-label">Deduction Type</label>
                        <select class="form-select" id="editDeductionType" name="deduction_type" required>
                            <option value="Fixed">Fixed</option>
                            <option value="Percentage">Percentage</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editAmount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="editAmount" name="amount" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="editPercentage" class="form-label">Percentage</label>
                        <input type="number" class="form-control" id="editPercentage" name="percentage" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="editMaxCap" class="form-label">Max Cap</label>
                        <input type="number" class="form-control" id="editMaxCap" name="max_cap" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="editIsMandatory" class="form-label">Is Mandatory</label>
                        <select class="form-select" id="editIsMandatory" name="is_mandatory" required>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
            <button type="submit" class="btn btn-success">Save Changes</button>
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
                </div>
        </div>
    </div>
</div>

<!-- Deactivate Deduction Modal -->
<div class="modal fade" id="deactivateDeductionModal" tabindex="-1" aria-labelledby="deactivateDeductionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deactivateDeductionModalLabel">Confirm Deactivation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deactivateDeductionForm">
                <input type="hidden" id="deactivateDeductionId" name="id">
                <div class="modal-body">
                    <p>Are you sure you want to deactivate this deduction?</p>
                </div>
            </form>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger" form="deactivateDeductionForm">
                    <i class="fa fa-trash"></i> Deactivate
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i> Cancel
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
                <h5 class="modal-title" id="ArchivesModalLabel">Archived Deductions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
                    <?php
                    include 'db_config.php';
                    $sql = "SELECT id, name, description, deduction_type, amount, percentage, max_cap, is_mandatory, status
                            FROM deductions
                            WHERE status = 'Inactive'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0): ?>
                        <div style="max-height: 680px; overflow-y: auto;">
                            <table class="table table-borderless table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="hidden-id">ID</th>
                                        <th style="min-width: 150px;">Name</th>
                                        <th style="min-width: 150px;">Description</th>
                                        <th style="min-width: 150px;">Deduction Type</th>
                                        <th style="min-width: 150px;">Amount</th>
                                        <th style="min-width: 150px;">Percentage</th>
                                        <th style="min-width: 150px;">Max Cap</th>
                                        <th style="min-width: 150px;">Is Mandatory</th>
                                        <th style="min-width: 150px;">Status</th>
                                        <th style="min-width: 150px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="archivedDeductionsTable">
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td class="hidden-id"><?= htmlspecialchars($row["id"]) ?></td>
                                            <td><?= htmlspecialchars($row["name"]) ?></td>
                                            <td><?= htmlspecialchars($row["description"]) ?></td>
                                            <td><?= htmlspecialchars($row["deduction_type"]) ?></td>
                                            <td><?= htmlspecialchars($row["amount"]) ?></td>
                                            <td><?= htmlspecialchars($row["percentage"]) ?></td>
                                            <td><?= htmlspecialchars($row["max_cap"]) ?></td>
                                            <td><?= htmlspecialchars($row["is_mandatory"]) ?></td>
                                            <td><?= htmlspecialchars($row["status"]) ?></td>
                                            <td>
                                                <button class="btn btn-success btn-sm"
                                                    data-id="<?= htmlspecialchars($row["id"]) ?>"
                                                    onclick="restoreDeduction(this)">
                                                    <i class="fa fa-undo"></i> Restore
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-4">
                            <h5 class="text-muted">No records found.</h5>
                        </div>
                    <?php endif; ?>
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

<!-- Restore Confirmation Modal -->
<div class="modal fade" id="restoreConfirmationModal" tabindex="-1" aria-labelledby="restoreConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restoreConfirmationModalLabel">Confirm Restoration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to restore this deduction?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="confirmRestoreBtn">
                    <i class="fa fa-undo"></i> Restore
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i> Cancel
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
    <!-- JavaScript to Toggle Fields Based on Deduction Type -->
    <script>
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

        document.getElementById("deductionType").addEventListener("change", function () {
            let type = this.value;
            document.getElementById("amountField").style.display = (type === "fixed") ? "block" : "none";
            document.getElementById("percentageField").style.display = (type === "percentage") ? "block" : "none";
            document.getElementById("maxCapField").style.display = (type === "percentage") ? "block" : "none";
        });

        $(document).ready(function () {
    $("#addDeductionForm").submit(function (e) {
        e.preventDefault(); // Prevent page reload

        $.ajax({
            url: "logics/deduction_logic.php", // Adjust based on your file structure
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    toastr.success(response.message);
                    $("#addDeductionsModal").modal("hide");
                    $("#addDeductionForm")[0].reset(); // Reset form
                    // Optionally, refresh the deductions table without reloading
                    fetchDeductions();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function () {
                toastr.error("An unexpected error occurred.");
            }
        });
    });

    // Fetch deductions to update table dynamically (Optional)
    function fetchDeductions() {
        $.ajax({
            url: "includes/deduction_fetch.php", // Create this PHP file to fetch updated data
            type: "GET",
            success: function (data) {
                $("#deductionsTable").html(data);
            }
        });
    }
});

    </script>

<script>
    // Populate edit modal with existing data
    document.querySelectorAll('[data-bs-target="#editDeductionModal"]').forEach(button => {
        button.addEventListener('click', () => {
            const row = button.closest('tr');
            document.getElementById('editDeductionId').value = row.cells[0].textContent;
            document.getElementById('editName').value = row.cells[1].textContent;
            document.getElementById('editDescription').value = row.cells[2].textContent;
            document.getElementById('editDeductionType').value = row.cells[3].textContent;
            document.getElementById('editAmount').value = row.cells[4].textContent;
            document.getElementById('editPercentage').value = row.cells[5].textContent;
            document.getElementById('editMaxCap').value = row.cells[6].textContent;
            document.getElementById('editIsMandatory').value = row.cells[7].textContent === 'Yes' ? '1' : '0';
        });
    });

    // Handle edit form submission
    document.getElementById('editDeductionForm').addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);

        fetch('logics/deduction_update.php', {
            method: 'POST',
            body: formData
        }).then(response => response.json()).then(data => {
            if (data.success) {
                toastr.success('Deduction updated successfully!');
                location.reload();
            } else {
                toastr.error('Failed to update deduction!');
            }
        }).catch(error => console.error('Error:', error));
    });

// Handle deactivate confirmation modal
function deactivateDeduction(button) {
    const id = button.getAttribute('data-id');
    document.getElementById('deactivateDeductionId').value = id;

    const modal = new bootstrap.Modal(document.getElementById('deactivateDeductionModal'));
    modal.show();
}

document.getElementById('deactivateDeductionForm').addEventListener('submit', (e) => {
    e.preventDefault();

    const id = document.getElementById('deactivateDeductionId').value;
    const formData = new FormData();
    formData.append('id', id);

    fetch('logics/deduction_deactivate.php', {
    method: 'POST',
    body: formData
})
.then(response => response.text()) // Change from .json() to .text()
.then(data => {
    try {
        let jsonData = JSON.parse(data);
        if (jsonData.success) {
            toastr.success(jsonData.message);
            const modal = bootstrap.Modal.getInstance(document.getElementById('deactivateDeductionModal'));
            modal.hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            toastr.error(jsonData.message);
        }
    } catch (error) {
        console.error("Invalid JSON Response:", data);
        toastr.error("An unexpected error occurred");
    }
})
.catch(error => {
    console.error("Fetch Error:", error);
    toastr.error("Failed to communicate with the server");
});

});

 // Variable to store the deduction ID to be restored
 let deductionIdToRestore = null;

 function restoreDeduction(button) {
    deductionIdToRestore = button.getAttribute('data-id');

    // Close the Archives modal first
    const archivesModal = bootstrap.Modal.getInstance(document.getElementById('ArchivesModal'));
    archivesModal.hide();

    // Then show the restore confirmation modal
    setTimeout(() => {
        const restoreModal = new bootstrap.Modal(document.getElementById('restoreConfirmationModal'));
        restoreModal.show();
    }, 300); // Small delay to ensure smooth transition between modals
}
// Handle the confirmation button click
document.getElementById('confirmRestoreBtn').addEventListener('click', function() {
    if (deductionIdToRestore) {
        // Original restore functionality
        $.ajax({
            url: 'logics/deduction_restore.php',
            type: 'POST',
            data: { id: deductionIdToRestore },
            success: function(response) {
                try {
                    const result = JSON.parse(response);
                    if (result.success) {
                        toastr.success('Deduction restored successfully!');
                        // Close the modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('restoreConfirmationModal'));
                        modal.hide();
                        // Reload the page to refresh the data after a timeout
                        setTimeout(function() {
                            location.reload();
                        }, 800); // 0.8 sec
                    } else {
                        toastr.error('Error: ' + result.message);
                    }
                } catch (e) {
                    toastr.error('Error processing response');
                    console.error(e);
                }
            },
            error: function() {
                toastr.error('Server error occurred');
            }
        });
    }
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    function filterCards() {
        let input = document.getElementById("searchCards").value.toLowerCase();
        let deductionsCard = document.getElementById("deductionsCard");
        let sssCard = document.getElementById("sssDeductionsCard");

        // Debugging: Log input and innerText
        console.log("Search Input:", input);
        console.log("Deductions Card Text:", deductionsCard.innerText.toLowerCase());
        console.log("SSS Card Text:", sssCard.innerText.toLowerCase());

        // Check if "SSS" exists in deductionsCard (where it's actually written)
        let showDeductions = deductionsCard.innerText.toLowerCase().includes(input);

        // Check for "SSS" in a specific part of the sssCard (e.g., column titles, first row, etc.)
        let showSSS = input.includes("sss") || sssCard.innerText.toLowerCase().includes(input);

        // Show or hide the cards based on search input
        deductionsCard.style.display = showDeductions ? "" : "none";
        sssCard.style.display = showSSS ? "" : "none";
    }

    document.getElementById("searchCards").addEventListener("keyup", filterCards);
});
</script>
</body>
</html>
