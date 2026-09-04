<?php
require_once __DIR__ . '/includes/staff_session.php';

error_reporting(E_ALL);
ini_set('display_errors', '0');

include 'staff_helper.php';
include 'db_config.php';

if (!isset($_SESSION['employee_id']) || !in_array(strtolower($_SESSION['role'] ?? ''), ['staff', 'intern'], true)) {
    header("Location: index.php");
    exit();
}

// Assign session values
$employee_id = $_SESSION['employee_id']; // This is the staff member reporting the incident

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Incident Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">

</head>
<body class="bg-light">

<div class="main-container">
        <?php include 'staff_navbar.php'; ?>
    </div>

    <div style="position: absolute; top: 7px; left: 20px; z-index: 1000;">
    <a href="file_reports.php" class="btn btn-secondary">
        <i class="fa fa-arrow-left"></i> Back to File Reports
    </a>
</div>


<!-- Animated Box Shapes -->
<div class="animation-container">
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
</div>

    <div class="container py-5">


    <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: linear-gradient(to bottom right, #f8f9fa, #e9ecef);">
    <div class="card-header text-white text-center rounded-top-4" style="background: linear-gradient(135deg, #6a11cb, #2575fc); box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
        <h3 class="mb-0 fw-bold"><i class="fas fa-file-alt"></i> Incident Report Form</h3>
    </div>

    <div class="card-body p-4">
        <form action="incident_report_logic.php" method="POST">
            <?= hshr_csrf_field() ?>
            <input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>">

            <div class="p-3 rounded shadow-sm" style="background: white;">
                <h5 class="fw-bold text-primary"><i class="fas fa-calendar"></i> Incident Details</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold"><i class="fas fa-calendar-alt"></i> Incident Date</label>
                        <input type="date" name="incident_date" class="form-control border-0 shadow-sm" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold"><i class="fas fa-clock"></i> Incident Time</label>
                        <input type="time" name="incident_time" class="form-control border-0 shadow-sm" required>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label fw-bold"><i class="fas fa-map-marker-alt"></i> Location</label>
                    <input type="text" name="location" class="form-control border-0 shadow-sm" placeholder="Enter location" required>
                </div>

                <div class="mt-3">
                    <label class="form-label fw-bold"><i class="fas fa-exclamation-triangle"></i> Incident Type</label>
                    <select name="incident_type" class="form-select border-0 shadow-sm" onchange="checkIncidentType(this)" required>
                        <option value="" disabled selected>Select Incident Type</option>
                        <option value="Injury">Injury</option>
                        <option value="Accident">Accident</option>
                        <option value="Misbehaviour">Misbehaviour</option>
                        <option value="Property Damage">Property Damage</option>
                        <option value="Others">Others</option>
                    </select>
                    <input type="text" name="custom_incident_type" id="custom_incident_type" class="form-control mt-2 border-0 shadow-sm" placeholder="Specify incident type" style="display: none;">
                </div>
            </div>

            <div class="mt-4 p-3 rounded shadow-sm" style="background: white;">
                <h5 class="fw-bold text-primary"><i class="fas fa-user-friends"></i> People & Description</h5>
                <div class="mt-3">
                    <label class="form-label fw-bold"><i class="fas fa-users"></i> Persons Involved</label>
                    <input type="text" name="persons_involved" class="form-control border-0 shadow-sm" placeholder="Enter names" required>
                </div>

                <div class="mt-3">
                    <label class="form-label fw-bold"><i class="fas fa-align-left"></i> Description</label>
                    <textarea name="description" class="form-control border-0 shadow-sm" rows="3" placeholder="Describe the incident" required></textarea>
                </div>
            </div>

            <div class="mt-4 p-3 rounded shadow-sm" style="background: white;">
                <h5 class="fw-bold text-primary"><i class="fas fa-tools"></i> Actions & Impact</h5>
                <div class="mt-3">
                    <label class="form-label fw-bold"><i class="fas fa-tools"></i> Action Taken</label>
                    <textarea name="action_taken" class="form-control border-0 shadow-sm" rows="3" placeholder="Describe actions taken" required></textarea>
                </div>

                <div class="row g-3 mt-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold"><i class="fas fa-bullseye"></i> Cause</label>
                        <input type="text" name="cause" class="form-control border-0 shadow-sm" placeholder="Enter cause" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold"><i class="fas fa-chart-line"></i> Impact</label>
                        <input type="text" name="impact" class="form-control border-0 shadow-sm" placeholder="Enter impact" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold"><i class="fas fa-exclamation-circle"></i> Severity</label>
                        <input type="text" name="severity" class="form-control border-0 shadow-sm" placeholder="Enter severity" required>
                    </div>
                </div>
            </div>

            <div class="mt-4 p-3 rounded shadow-sm" style="background: white;">
                <h5 class="fw-bold text-primary"><i class="fas fa-lightbulb"></i> Recommendations & Follow-Up</h5>
                <div class="mt-3">
                    <label class="form-label fw-bold"><i class="fas fa-lightbulb"></i> Prevention Recommendations</label>
                    <textarea name="recommendations" class="form-control border-0 shadow-sm" rows="3" placeholder="Provide recommendations" required></textarea>
                </div>

                <div class="mt-3">
                    <label class="form-label fw-bold"><i class="fas fa-gavel"></i> Disciplinary Actions</label>
                    <textarea name="disciplinary_actions" class="form-control border-0 shadow-sm" rows="3" placeholder="Describe disciplinary actions" required></textarea>
                </div>

                <div class="mt-3">
                    <label class="form-label fw-bold"><i class="fas fa-hands-helping"></i> Additional Support Needed</label>
                    <textarea name="additional_support" class="form-control border-0 shadow-sm" rows="3" placeholder="Specify additional support" required></textarea>
                </div>

                <div class="mt-3">
                    <label class="form-label fw-bold"><i class="fas fa-tasks"></i> Follow-Up Procedures</label>
                    <textarea name="follow_up" class="form-control border-0 shadow-sm" rows="3" placeholder="Describe follow-up procedures" required></textarea>
                </div>

                <div class="mt-3">
                    <label class="form-label fw-bold"><i class="fas fa-clipboard-check"></i> Conclusion</label>
                    <textarea name="conclusion" class="form-control border-0 shadow-sm" rows="3" placeholder="Provide conclusion" required></textarea>
                </div>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-lg rounded-pill text-white" style="background: linear-gradient(135deg, #ff416c, #ff4b2b); box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-paper-plane"></i> Submit Report
                </button>
            </div>
        </form>
    </div>
</div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function checkIncidentType(select) {
            let customField = document.getElementById("custom_incident_type");
            customField.style.display = (select.value === "Others") ? "block" : "none";
        }
    </script>

<script>
document.querySelector("form").addEventListener("submit", function(event) {
    let textareas = document.querySelectorAll("textarea[required]");
    let isValid = true;

    textareas.forEach(textarea => {
        if (!textarea.value.trim()) {
            isValid = false;
            textarea.style.border = "2px solid red"; // Highlight empty fields
        } else {
            textarea.style.border = "";
        }
    });

    if (!isValid) {
        event.preventDefault(); // Stop form submission
        alert("Please fill in all required fields.");
    }
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    <?php if (isset($_SESSION['success_message'])) { ?>
        Swal.fire({
            icon: 'success',
            title: <?= json_encode((string) $_SESSION['success_message'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
            showConfirmButton: false,
            timer: 2000
        });
        <?php unset($_SESSION['success_message']); ?>
    <?php } ?>

    <?php if (isset($_SESSION['error_message'])) { ?>
        Swal.fire({
            icon: 'error',
            title: <?= json_encode((string) $_SESSION['error_message'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
        <?php unset($_SESSION['error_message']); ?>
    <?php } ?>
});
</script>




</body>
</html>
