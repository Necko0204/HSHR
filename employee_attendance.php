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
            <h2 class="fw-bold mb-0"><i class="fa fa-calendar-check"></i> Employee Attendance</h2>
        </div>
<!-- Employee Attendance -->
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
            $sql = "SELECT a.id, a.employee_id, e.firstname, e.lastname, a.date, a.time_in, a.time_out, a.total_hours, a.image_path,
                        a.break_in, a.break_out, a.break_duration, a.status 
                    FROM attendance a
                    JOIN employees e ON a.employee_id = e.id";
            $result = $conn->query($sql);
            ?>

            <div style="max-height: 530px; overflow-y: auto;">
                <?php if ($result->num_rows > 0): ?>
                    <table class="table table-borderless table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="hidden-id" style="min-width: 100px;">ID</th>
                                <th style="min-width: 150px;">Proof of Time in</th>
                                <th style="min-width: 150px;">Employee Name</th>
                                <th style="min-width: 150px;">Date</th>
                                <th style="min-width: 150px;">Time In</th>
                                <th style="min-width: 150px;">Time Out</th>
                                <th style="min-width: 150px;">Total Hours</th>
                                <th style="min-width: 150px;">Break In</th>
                                <th style="min-width: 150px;">Break Out</th>
                                <th style="min-width: 150px;">Break Duration</th>
                                <th style="min-width: 150px;">Status</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceTable">
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="hidden-id"><?= htmlspecialchars($row["id"]) ?></td>
                                    <td><img src="staff_side/<?= htmlspecialchars($row["image_path"]) ?>" alt="Proof of Time in" class="img-thumbnail" style="width: 100px; height: 100px;" ></td>
                                    <td><?= htmlspecialchars($row["firstname"] . ' ' . $row["lastname"]) ?></td>
                                    <td><?= htmlspecialchars($row["date"]) ?></td>
                                    <td><?= htmlspecialchars($row["time_in"]) ?></td>
                                    <td><?= htmlspecialchars($row["time_out"]) ?></td>
                                    <td><?= htmlspecialchars($row["total_hours"]) ?></td>
                                    <td><?= htmlspecialchars($row["break_in"]) ?></td>
                                    <td><?= htmlspecialchars($row["break_out"]) ?></td>
                                    <td><?= htmlspecialchars($row["break_duration"]) ?></td>
                                    <td>
                                        <span class="badge <?= $row["status"] == 'Present' ? 'bg-gradient-success text-white' : 'bg-gradient-danger text-white' ?>">
                                            <?= htmlspecialchars($row["status"]) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="text-center p-4">No attendance records found.</div>
                <?php endif; ?>
            </div>
            <?php $conn->close(); ?>
        </div>
    </div>
</div>
<!-- End Employee Attendance -->
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

                <div class="tab-content" id="archivesTabsContent">
                    
                    <?php
                    include 'db_config.php';
                    $sql = "SELECT a.id, a.employee_id, e.firstname, e.lastname, a.date, a.time_in, a.time_out, a.total_hours, 
                                a.break_in, a.break_out, a.break_duration, a.status 
                            FROM attendance a
                            JOIN employees e ON a.employee_id = e.id
                            WHERE a.status IN ('Approved', 'Rejected')";
                    $result = $conn->query($sql);

                    // Initialize arrays to store data
                    $approvedRecords = [];
                    $rejectedRecords = [];

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            if ($row['status'] === 'Approved') {
                                $approvedRecords[] = $row;
                            } elseif ($row['status'] === 'Rejected') {
                                $rejectedRecords[] = $row;
                            }
                        }
                    }
                    ?>

                    <!-- Approved Tab Content -->
                    <div class="tab-pane fade show active" id="approved-content" role="tabpanel">
                        <?php if (!empty($approvedRecords)) : ?>
                            <div style="max-height: 530px; overflow-y: auto;">
                                <table class="table table-borderless table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="min-width: 100px;">ID</th>
                                            <th style="min-width: 150px;">Employee Name</th>
                                            <th style="min-width: 150px;">Date</th>
                                            <th style="min-width: 150px;">Time In</th>
                                            <th style="min-width: 150px;">Time Out</th>
                                            <th style="min-width: 150px;">Total Hours</th>
                                            <th style="min-width: 150px;">Break In</th>
                                            <th style="min-width: 150px;">Break Out</th>
                                            <th style="min-width: 150px;">Break Duration</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($approvedRecords as $row) : ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row["id"]) ?></td>
                                                <td><?= htmlspecialchars($row["firstname"] . ' ' . $row["lastname"]) ?></td>
                                                <td><?= htmlspecialchars($row["date"]) ?></td>
                                                <td><?= htmlspecialchars($row["time_in"]) ?></td>
                                                <td><?= htmlspecialchars($row["time_out"]) ?></td>
                                                <td><?= htmlspecialchars($row["total_hours"]) ?></td>
                                                <td><?= htmlspecialchars($row["break_in"]) ?></td>
                                                <td><?= htmlspecialchars($row["break_out"]) ?></td>
                                                <td><?= htmlspecialchars($row["break_duration"]) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else : ?>
                            <div class="text-center p-4">No approved attendance records found.</div>
                        <?php endif; ?>
                    </div>

                    <!-- Rejected Tab Content -->
                    <div class="tab-pane fade" id="rejected-content" role="tabpanel">
                        <?php if (!empty($rejectedRecords)) : ?>
                            <div style="max-height: 530px; overflow-y: auto;">
                                <table class="table table-borderless table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="min-width: 100px;">ID</th>
                                            <th style="min-width: 150px;">Employee Name</th>
                                            <th style="min-width: 150px;">Date</th>
                                            <th style="min-width: 150px;">Time In</th>
                                            <th style="min-width: 150px;">Time Out</th>
                                            <th style="min-width: 150px;">Total Hours</th>
                                            <th style="min-width: 150px;">Break In</th>
                                            <th style="min-width: 150px;">Break Out</th>
                                            <th style="min-width: 150px;">Break Duration</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($rejectedRecords as $row) : ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row["id"]) ?></td>
                                                <td><?= htmlspecialchars($row["firstname"] . ' ' . $row["lastname"]) ?></td>
                                                <td><?= htmlspecialchars($row["date"]) ?></td>
                                                <td><?= htmlspecialchars($row["time_in"]) ?></td>
                                                <td><?= htmlspecialchars($row["time_out"]) ?></td>
                                                <td><?= htmlspecialchars($row["total_hours"]) ?></td>
                                                <td><?= htmlspecialchars($row["break_in"]) ?></td>
                                                <td><?= htmlspecialchars($row["break_out"]) ?></td>
                                                <td><?= htmlspecialchars($row["break_duration"]) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else : ?>
                            <div class="text-center p-4">No rejected attendance records found.</div>
                        <?php endif; ?>
                    </div>

                </div> <!-- End of Tab Content -->

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<?php $conn->close(); ?>


    <!-- Bootstrap JS -->
    <script src="background.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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

    document.getElementById("searchInput").addEventListener("keyup", function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll("#attendanceTable tr");

        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? "" : "none";
        });
    });
    </script>
</body>
</html>
