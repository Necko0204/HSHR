<?php
session_name('admin_session');
session_start();

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
            <h2 class="fw-bold mb-0">
                <i class="fa fa-chalkboard-teacher"></i> Teacher Applications
            </h2>
        </div>

<!-- APPLICATION LIST TABLE -->
<div class="card shadow-lg border-1 rounded-3">
    <div class="card-header bg-gradient-primary text-black d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <input type="text" id="searchInput" class="form-control" placeholder="Search..." style="width: 150px;">
        </div>
        <div class="d-flex align-items-center">
            <button type="button" class="btn btn-secondary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#ArchivesModal">
                <i class="fa fa-folder"></i> <span>Archives</span>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive" style="overflow-x: auto; white-space: nowrap">
            <?php
                $sql = "SELECT * FROM applicants WHERE LOWER(status) IN ('pending', 'for_interview') ORDER BY CAST(SUBSTRING(applicant_id, 10) AS UNSIGNED)";
                $result = $conn->query($sql);
            ?>
            <div style="max-height: 530px; overflow-y: auto;">
                <?php if ($result->num_rows > 0): ?>
                    <table class="table table-borderless table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="min-width: 150px;">Applicant No.</th>
                                <th style="min-width: 150px;">Last Name</th>    
                                <th style="min-width: 150px;">First Name</th>
                                <th style="min-width: 150px;">Middle Name</th>
                                <th style="min-width: 150px;">Email</th>
                                <th style="min-width: 150px;">Gender</th>   
                                <th style="min-width: 150px;">Date of Birth</th>
                                <th style="min-width: 150px;">Contact</th>
                                <th style="min-width: 150px;">Resume</th>
                                <th style="min-width: 150px;">Submitted At</th>
                                <th style="min-width: 150px;">Status</th>
                                <th style="min-width: 150px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="applicantsTable">
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row["applicant_id"]) ?></td>
                                    <td><?= htmlspecialchars($row["lastname"]) ?></td>
                                    <td><?= htmlspecialchars($row["firstname"]) ?></td>
                                    <td><?= htmlspecialchars($row["middlename"]) ?></td>
                                    <td><?= htmlspecialchars($row["email"]) ?></td>
                                    <td><?= htmlspecialchars($row["gender"]) ?></td> 
                                    <td><?= htmlspecialchars($row["dateofbirth"]) ?></td>
                                    <td><?= htmlspecialchars($row["contact"]) ?></td>
                                    <td><a href="<?= htmlspecialchars($row["resume_path"]) ?>" target="_blank">View Resume</a></td>
                                    <td><?= htmlspecialchars($row["submitted_at"]) ?></td>
                                    <td>
                                        <?php 
                                                $status = htmlspecialchars($row["status"]);
                                                $badgeClass = "";
                                                $displayText = $status; // Default display text

                                                switch (strtolower($status)) {
                                                    case "pending":
                                                        $badgeClass = "bg-warning text-dark"; // Yellow
                                                        break;
                                                    case "for_interview":
                                                        $badgeClass = "bg-warning text-dark"; // Yellow
                                                        $displayText = "For Interview"; // Adjust display text
                                                        break;
                                                    case "accepted":
                                                        $badgeClass = "bg-success text-white"; // Green
                                                        break;
                                                    default:
                                                        $badgeClass = "bg-secondary text-white"; // Default (gray) if unknown status
                                                }
                                            ?>
                                            <span class="badge <?= $badgeClass; ?>"><?= $displayText; ?></span>
                                    </td>
                                    <td>
                                        <?php if (strtolower($row["status"]) !== "for_interview"): ?>
                                            <button type="button" class="btn btn-primary btn-sm scheduleBtn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#scheduleModal"
                                                data-applicant-id="<?= htmlspecialchars($row["applicant_id"]) ?>"
                                                data-email="<?= htmlspecialchars($row["email"]) ?>">
                                                <i class="fa fa-calendar"></i> Set Schedule
                                            </button>
                                        <?php endif; ?>
                                        <?php if (strtolower($row["status"]) === "for_interview"): ?>
                                            <button type="button" class="btn btn-success btn-sm acceptBtn text-white"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#acceptModal"
                                                data-applicant-id="<?= htmlspecialchars($row["applicant_id"]) ?>"
                                                data-email="<?= htmlspecialchars($row["email"]) ?>">
                                                <i class="fa fa-check"></i> Accept
                                            </button>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-danger btn-sm rejectBtn text-white"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#rejectModal"
                                            data-applicant-id="<?= htmlspecialchars($row["applicant_id"]) ?>"
                                            data-email="<?= htmlspecialchars($row["email"]) ?>">
                                            <i class="fa fa-times"></i> Reject
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="text-center p-4">No pending applications.</div>
                <?php endif; ?>
            </div>
            <?php $conn->close(); ?>
        </div>
    </div>
</div>
</main>

<!-- Accept Modal -->
<div class="modal fade" id="acceptModal" tabindex="-1" aria-labelledby="acceptModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="acceptModalLabel">Accept Applicant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to accept this applicant?</p>
                <form id="acceptForm">
                    <input type="hidden" id="applicant_id" name="applicant_id">
                    <input type="hidden" id="email" name="email">
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" id="acceptBtn" form="acceptForm" class="btn btn-success">
                    <i class="fa fa-check"></i> <span class="btn-text">Accept</span>
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i> Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleModalLabel">Select Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="scheduleForm" action="send_schedule.php" method="post">
                    <input type="hidden" id="schdle_applicant_id" name="applicant_id">
                    <input type="hidden" id="schdle_email" name="email">
                    
                    <div class="mb-3">
                        <label for="schedule" class="form-label">Choose a schedule:</label>
                        <input type="text" id="schedule" name="schedule" class="form-control" placeholder="Set Schedule">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="scheduleForm" class="btn btn-primary">
                    <i class="fa fa-check"></i> Confirm
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Applicant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reject this applicant?</p>
                <form id="rejectForm" action="reject_applicant.php" method="post">
                    <input type="hidden" id="reject_applicant_id" name="applicant_id">
                    <input type="hidden" id="reject_email" name="email">
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="rejectForm" class="btn btn-danger">
                    <i class="fa fa-times"></i> Reject
                </button>
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                    <i class="fa fa-check"></i> Cancel
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
                <!-- Tabs for Accepted & Rejected -->
                <ul class="nav nav-pills mb-3 justify-content-center border-bottom pb-2" id="archivesTabs" role="tablist">
                    <li class="nav-item mx-3" role="presentation">
                        <button class="nav-link active" id="accepted-tab" data-bs-toggle="pill" data-bs-target="#accepted-content" type="button" role="tab">
                            <i class="fas fa-check-circle"></i> Accepted
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
                    <!-- Accepted Applicants -->
                    <div class="tab-pane fade show active" id="accepted-content" role="tabpanel">
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <?php
                            include 'db_config.php';
                            $sql = "SELECT * FROM applicants WHERE status = 'Accepted'";
                            $result = $conn->query($sql);
                            ?>
                            <?php if ($result->num_rows > 0): ?>
                                <table class="table table-borderless table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="min-width: 150px;">Applicant No.</th>
                                            <th style="min-width: 150px;">Last Name</th>
                                            <th style="min-width: 150px;">First Name</th>
                                            <th style="min-width: 150px;">Middle Name</th>
                                            <th style="min-width: 150px;">Email</th>
                                            <th style="min-width: 150px;">Gender</th>
                                            <th style="min-width: 150px;">Date of Birth</th>
                                            <th style="min-width: 150px;">Contact</th>
                                            <th style="min-width: 150px;">Resume</th>
                                            <th style="min-width: 150px;">Submitted At</th>
                                            <th style="min-width: 150px;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row["applicant_id"]) ?></td>
                                                <td><?= htmlspecialchars($row["lastname"]) ?></td>
                                                <td><?= htmlspecialchars($row["firstname"]) ?></td>
                                                <td><?= htmlspecialchars($row["middlename"]) ?></td>
                                                <td><?= htmlspecialchars($row["email"]) ?></td>
                                                <td><?= htmlspecialchars($row["gender"]) ?></td>
                                                <td><?= htmlspecialchars($row["dateofbirth"]) ?></td>
                                                <td><?= htmlspecialchars($row["contact"]) ?></td>
                                                <td><a href="<?= htmlspecialchars($row["resume_path"]) ?>" target="_blank">View Resume</a></td>
                                                <td><?= htmlspecialchars($row["submitted_at"]) ?></td>
                                                <td><span class="badge bg-success text-white">Accepted</span></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="text-center p-4">No accepted applications.</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Rejected Applicants -->
                    <div class="tab-pane fade" id="rejected-content" role="tabpanel">
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <?php
                            $sql = "SELECT * FROM applicants WHERE status = 'Rejected'";
                            $result = $conn->query($sql);
                            ?>
                            <?php if ($result->num_rows > 0): ?>
                                <table class="table table-borderless table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="min-width: 150px;">Applicant No.</th>
                                            <th style="min-width: 150px;">Last Name</th>
                                            <th style="min-width: 150px;">First Name</th>
                                            <th style="min-width: 150px;">Middle Name</th>
                                            <th style="min-width: 150px;">Email</th>
                                            <th style="min-width: 150px;">Gender</th>
                                            <th style="min-width: 150px;">Date of Birth</th>
                                            <th style="min-width: 150px;">Contact</th>
                                            <th style="min-width: 150px;">Resume</th>
                                            <th style="min-width: 150px;">Submitted At</th>
                                            <th style="min-width: 150px;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row["applicant_id"]) ?></td>
                                                <td><?= htmlspecialchars($row["lastname"]) ?></td>
                                                <td><?= htmlspecialchars($row["firstname"]) ?></td>
                                                <td><?= htmlspecialchars($row["middlename"]) ?></td>
                                                <td><?= htmlspecialchars($row["email"]) ?></td>
                                                <td><?= htmlspecialchars($row["gender"]) ?></td>
                                                <td><?= htmlspecialchars($row["dateofbirth"]) ?></td>
                                                <td><?= htmlspecialchars($row["contact"]) ?></td>
                                                <td><a href="<?= htmlspecialchars($row["resume_path"]) ?>" target="_blank">View Resume</a></td>
                                                <td><?= htmlspecialchars($row["submitted_at"]) ?></td>
                                                <td><span class="badge bg-danger text-white">Rejected</span></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="text-center p-4">No rejected applications.</div>
                            <?php endif; ?>
                        </div>
                    </div>
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

    <!-- jQuery and Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="background.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
document.addEventListener("DOMContentLoaded", function() {
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

    // Ensure elements exist before using them
    const scheduleInput = document.getElementById("schedule");
if (scheduleInput) {
    flatpickr(scheduleInput, {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today",
        time_24hr: true,
        minTime: "08:00",
        maxTime: "17:00",
        onOpen: function(selectedDates, dateStr, instance) {
            if (!document.querySelector(".flatpickr-footer")) {
                let footer = document.createElement("div");
                footer.classList.add("flatpickr-footer");
                footer.style.cssText = "padding: 5px; display: flex; justify-content: space-between;";

                let closeButton = document.createElement("button");
                closeButton.innerHTML = "Close";
                closeButton.style.cssText = "padding: 5px; background: red; color: white; border: none; cursor: pointer;";

                let clearButton = document.createElement("button");
                clearButton.innerHTML = "Clear";
                clearButton.style.cssText = "padding: 5px; background: gray; color: white; border: none; cursor: pointer;";

                closeButton.addEventListener("click", function () {
                    instance.close();
                });

                clearButton.addEventListener("click", function () {
                    instance.clear();
                });

                footer.appendChild(clearButton);
                footer.appendChild(closeButton);
                instance.calendarContainer.appendChild(footer);
            }
        }
    });
}

    // Schedule Button Click Event
    document.querySelectorAll(".scheduleBtn").forEach(button => {
        button.addEventListener("click", function() {
            const applicantId = this.getAttribute("data-applicant-id");
            const email = this.getAttribute("data-email");

            document.getElementById("schdle_applicant_id").value = applicantId;
            document.getElementById("schdle_email").value = email;
        });
    });

    // Reject Button Click Event
    document.querySelectorAll(".rejectBtn").forEach(button => {
        button.addEventListener("click", function() {
            document.getElementById("reject_applicant_id").value = this.getAttribute("data-applicant-id");
            document.getElementById("reject_email").value = this.getAttribute("data-email");
        });
    });

    // ACCEPT Event
    document.querySelectorAll('.acceptBtn').forEach(button => {
        button.addEventListener('click', function () {
            let applicantId = this.getAttribute('data-applicant-id');
            let email = this.getAttribute('data-email');

            console.log('Applicant ID:', applicantId); // Debugging to confirm data
            console.log('Email:', email);

            // Set the form values
            document.getElementById('applicant_id').value = applicantId;
            document.getElementById('email').value = email;
        });
    });


   // Handle Scheduling Form Submission with Loading State
document.getElementById("scheduleForm").addEventListener("submit", function (event) {
    event.preventDefault();

    const formData = new FormData(this);
    const submitButton = document.querySelector("#scheduleModal .btn-primary");

    if (submitButton.disabled) return; // Prevent multiple clicks

    // Log form data for debugging
    console.log("Submitting Schedule Form:");
    formData.forEach((value, key) => {
        console.log(`${key}: ${value}`);
    });

    // Show loading spinner
    submitButton.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sending...';
    submitButton.disabled = true;

    fetch("logics/send_schedule.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log("Server Response:", data);
        if (data.status === "success") {
            toastr.success(data.message, "Success");
            submitButton.innerHTML = '<i class="fa fa-check"></i> Sent!';
            setTimeout(() => location.reload(), 1500);
        } else {
            toastr.error(data.message, "Error");
            submitButton.innerHTML = "Confirm";
            submitButton.disabled = false;
        }
    })
    .catch(error => {
        console.error("Error:", error);
        toastr.error("An unexpected error occurred.", "Error");
        submitButton.innerHTML = "Confirm";
        submitButton.disabled = false;
    });
});

    // Handle Rejection Form Submission with Loading State
    document.getElementById("rejectForm").addEventListener("submit", function (event) {
        event.preventDefault();

        const formData = new FormData(this);
        const rejectButton = document.querySelector("#rejectModal .btn-danger");

        if (rejectButton.disabled) return; // Prevent multiple clicks

        // Show loading spinner
        rejectButton.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Rejecting...';
        rejectButton.disabled = true;

        fetch("logics/reject_applicant.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                toastr.success(data.message, "Success");
                rejectButton.innerHTML = '<i class="fa fa-check"></i> Rejected!';
                setTimeout(() => location.reload(), 1500);
            } else {
                toastr.error(data.message, "Error");
                rejectButton.innerHTML = "Reject";
                rejectButton.disabled = false; // Re-enable button on failure
            }
        })
        .catch(error => {
            console.error("Error:", error);
            toastr.error("An unexpected error occurred.", "Error");
            rejectButton.innerHTML = "Reject";
            rejectButton.disabled = false; // Re-enable button on error
        });
    });

    document.getElementById('acceptForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent default form submission

        let formData = new FormData(this);
        let applicantId = formData.get('applicant_id');
        console.log('Applicant ID:', applicantId); // Log the applicant ID to confirm it's being captured

        let acceptBtn = document.getElementById('acceptBtn');
        let btnText = acceptBtn.querySelector('.btn-text');

        // Disable button and show loading
        acceptBtn.disabled = true;
        btnText.innerHTML = 'Processing...';

        fetch('logics/application_acceptance_logic.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) // Expecting JSON response
        .then(data => {
            console.log('Response:', data); // Log the response from the server
            if (data.success) {
                toastr.success(data.message || 'Applicant accepted successfully!', 'Success');
                setTimeout(() => {
                    location.reload(); // Refresh the page after success
                }, 2000);
            } else {
                toastr.error(data.message || 'Failed to accept applicant. Please try again.', 'Error');
            }
        })
        .catch(error => {
            toastr.error('Something went wrong. Please try again.', 'Error');
            console.error('Error:', error);
        })
        .finally(() => {
            // Re-enable button
            acceptBtn.disabled = false;
            btnText.innerHTML = 'Accept';
        });
    });
});

document.getElementById("searchInput").addEventListener("keyup", function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#applicantsTable tr");

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});
</script>
</body>
</html>
