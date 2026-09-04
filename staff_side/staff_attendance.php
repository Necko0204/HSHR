<?php
require_once __DIR__ . '/includes/staff_session.php';

error_reporting(E_ALL);
ini_set('display_errors', '0');
include 'db_config.php';
include 'staff_helper.php';

if (!isset($_SESSION['employee_id']) || !in_array(strtolower($_SESSION['role'] ?? ''), ['staff', 'intern'], true)) {
    header("Location: index.php");
    exit();
}
// Retrieve the employee_id and role from the session
$employee_id = $_SESSION['employee_id'];
$sender_role = isset($_SESSION['role']) ? $_SESSION['role'] : "";

// Get the staff data using a helper function (assumed to be defined elsewhere)
$staffData = getStaffData($employee_id);

if (!$staffData) {
    die("❌ Staff data not found.");
}

// Prepare the query to retrieve attendance data
$query = "
    SELECT
        a.date,
        a.time_in,
        a.time_out,
        a.break_in,
        a.break_out,
        a.break_duration,
        a.total_hours,
        IFNULL(ou.status, 'on time') AS status,
        IFNULL(ou.hours, 0) AS hours
    FROM attendance a
    LEFT JOIN overtime_undertime_logs ou
        ON a.employee_id = ou.employee_id AND a.date = ou.date
    WHERE a.employee_id = ?
    ORDER BY a.date DESC
";

// Check if the user has an auto timeout on their last attendance record
$check_query = "
    SELECT status FROM attendance
    WHERE employee_id = ?
    ORDER BY date DESC, time_in DESC
    LIMIT 1
";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param("s", $employee_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();
$last_attendance = $check_result->fetch_assoc();

$auto_timeout_applied = ($last_attendance && $last_attendance['status'] === 'Auto_Timeout');

if ($auto_timeout_applied && !isset($_SESSION['auto_timeout_alert_shown'])) {
    $_SESSION['auto_timeout_alert_shown'] = true; // Set session to prevent repeat alerts
    $show_alert = true;
} else {
    $show_alert = false;
}

$stmt = $conn->prepare($query);
if (!$stmt) {
    error_log("Attendance query preparation failed: " . $conn->error);
    http_response_code(500);
    die("Attendance data is temporarily unavailable.");
}

// Bind the employee_id to the prepared statement
$stmt->bind_param("s", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="icon" type="image/png" href="../images/asdasdasd123123123123123.jpg">
    <title>Attendance</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .container-fluid.content-wrapper {
            padding: 0;
        }
        .card-custom2 {
            margin: 0;
            border-radius: 15px;
            overflow: hidden;
        }
    </style>
</head>
<body style="overflow: hidden;">


    <div style="position: absolute; top: 7px; left: 20px; z-index: 1000;">
        <a href="dashboard.php" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="main-container">
        <?php include 'staff_navbar.php'; ?>
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

    <div class="container-fluid content-wrapper py-4">
        <h2 class="mb-4 text-center fw-bold" style="color: black; letter-spacing: 1px;">
            Welcome, <?php echo htmlspecialchars($staffData['username']); ?>
        </h2>

        <div class="d-flex flex-wrap justify-content-center gap-3 mb-4">
            <button class="btn btn-success btn-lg shadow-sm" onclick="openClockInModal()">
                <i class="fa fa-sign-in-alt me-2"></i> Clock In
            </button>
            <button class="btn btn-danger btn-lg shadow-sm" onclick="clockOut()">
                <i class="fa fa-sign-out-alt me-2"></i> Clock Out
            </button>
            <button class="btn btn-primary btn-lg shadow-sm" onclick="breakIn()">
                <i class="fa fa-coffee me-2"></i> Break In
            </button>
            <button class="btn btn-warning btn-lg shadow-sm" onclick="breakOut()">
                <i class="fa fa-coffee me-2"></i> Break Out
            </button>
            <button class="btn btn-info btn-lg shadow-sm text-white" data-bs-toggle="modal" data-bs-target="#overtimeUndertimeModal">
                <i class="fa fa-clock me-2"></i> Overtime/Undertime Logs
            </button>
        </div>

        <div class="row justify-content-center g-4">
            <div class="col-lg-8">
                <div class="card card-custom2 shadow-sm">
                    <div class="card-body">
                        <h3 class="text-center mb-3 fw-semibold" style="color: black;">Attendance Log</h3>
                        <div class="table-responsive">
                            <table class="table table-striped align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Time In</th>
                                        <th>Time Out</th>
                                        <th>Total Hours</th>
                                        <th>Break In</th>
                                        <th>Break Out</th>
                                        <th>Break Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo isset($row['date']) ? htmlspecialchars($row['date']) : 'N/A'; ?></td>
                                            <td><?php echo !empty($row['time_in']) ? htmlspecialchars($row['time_in']) : 'N/A'; ?></td>
                                            <td><?php echo !empty($row['time_out']) ? htmlspecialchars($row['time_out']) : 'N/A'; ?></td>
                                            <td><?php echo !empty($row['total_hours']) ? htmlspecialchars($row['total_hours']) : 'N/A'; ?></td>
                                            <td><?php echo !empty($row['break_in']) ? htmlspecialchars($row['break_in']) : 'N/A'; ?></td>
                                            <td><?php echo !empty($row['break_out']) ? htmlspecialchars($row['break_out']) : 'N/A'; ?></td>
                                            <td><?php echo !empty($row['break_duration']) ? htmlspecialchars($row['break_duration']) : '00:00:00'; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-custom2 shadow-sm">
                    <div class="card-body">
                        <h3 class="text-center mb-3 fw-semibold" style="color: black;">Schedule</h3>
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Day</th>
                                    <th>Work Hours</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!isset($conn)) {
                                    echo "<tr><td colspan='2'>Database connection error</td></tr>";
                                    exit();
                                }

                                $scheduleQuery = "
                                SELECT day_of_week, required_hours
                                FROM work_schedules
                                WHERE employee_id = ?
                                ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
                                ";

                                $stmt = $conn->prepare($scheduleQuery);

                                if ($stmt) {
                                    $stmt->bind_param("s", $_SESSION['employee_id']);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    while ($row = $result->fetch_assoc()) {
                                        $day = htmlspecialchars($row['day_of_week']);
                                        $hours = ($row['required_hours'] == 0) ? "Off" : $row['required_hours'] . " hours";

                                        echo "<tr><td>{$day}</td><td>{$hours}</td></tr>";
                                    }

                                    $stmt->close();
                                } else {
                                    error_log('Schedule query failed: ' . $conn->error);
                                    echo "<tr><td colspan='2'>Schedule data is temporarily unavailable.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overtime/Undertime Modal -->
    <div class="modal fade" id="overtimeUndertimeModal" tabindex="-1" aria-labelledby="overtimeUndertimeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="overtimeUndertimeModalLabel">Overtime/Undertime Logs</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card card-custom2 w-100 mb-0">
                        <div class="card-body">
                            <h3 class="text-center mb-3 fw-semibold" style="color: black;">Overtime/Undertime</h3>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Work Hours</th>
                                            <th>Status</th>
                                            <th>Hours</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $default_work_hours = 8;

                                        $query = "SELECT a.date, a.total_hours, ou.status, ou.hours
                                                  FROM attendance a
                                                  LEFT JOIN overtime_undertime_logs ou ON a.employee_id = ou.employee_id AND a.date = ou.date
                                                  WHERE a.employee_id = ?";

                                        $stmt = $conn->prepare($query);
                                        $stmt->bind_param("s", $employee_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        while ($row = $result->fetch_assoc()) {
                                            $work_hours = isset($row['total_hours']) ? (float)$row['total_hours'] : 0;
                                            $status = isset($row['status']) ? htmlspecialchars($row['status']) : 'N/A';
                                            $hours = isset($row['hours']) ? (float)$row['hours'] : 0;
                                        ?>
                                            <tr>
                                                <td><?php echo isset($row['date']) ? htmlspecialchars($row['date']) : 'N/A'; ?></td>
                                                <td><?php echo $work_hours . " hrs"; ?></td>
                                                <td><?php echo $status; ?></td>
                                                <td><?php echo $hours > 0 ? $hours . " hrs" : "-"; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Clock In Modal -->
    <div class="modal fade" id="clockInModal" tabindex="-1" aria-labelledby="clockInModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="clockInModalLabel">Clock In - Capture Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <video id="cameraPreview" width="100%" style="border-radius: 10px; background: #f8f9fa;" autoplay></video>
                    <img id="photoPreview" src="" style="width: 100%; display: none; border-radius: 10px; margin-top: 10px;" alt="Captured Photo">
                    <canvas id="capturedCanvas" style="display: none;"></canvas>
                    <div class="mt-3 d-flex justify-content-center gap-2">
                        <button id="captureBtn" class="btn btn-primary" onclick="capturePhoto()">📸 Capture</button>
                        <button id="retakeBtn" class="btn btn-warning" onclick="retakePhoto()" style="display: none;">🔄 Retake</button>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button id="submitClockIn" class="btn btn-success" onclick="submitClockIn()" disabled>Submit</button>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if ($show_alert): ?>
        Swal.fire({
            title: "Auto Clock-Out Applied",
            text: "An auto clock-out was applied for your last attendance. Please remember to clock out next time.",
            icon: "warning",
            confirmButtonText: "OK"
        });
    <?php endif; ?>
});
</script>

<script>
    let videoStream;
    let capturedImage = null;



    // Check if already clocked in before opening modal
    async function checkClockInStatus() {
        try {
            let response = await fetch('check_clockin_status.php');
            let data = await response.json();
            return data.already_clocked_in;
        } catch (error) {
            console.error("Clock-in check error:", error);
            return false;
        }
    }


    // Function to open modal and start the camera
    async function openClockInModal() {
    let alreadyClockedIn = await checkClockInStatus();

    if (alreadyClockedIn) {
        Swal.fire({
            icon: 'info',
            title: 'Already Clocked In',
            text: 'You have already clocked in today.',
            confirmButtonText: 'OK'
        });
        return;
    }

    const modal = new bootstrap.Modal(document.getElementById('clockInModal'));
    modal.show();

    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            videoStream = stream;
            document.getElementById('cameraPreview').srcObject = stream;
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Camera Error',
                text: 'Error accessing camera: ' + error
            });
        });
}

    // Function to capture photo
    function capturePhoto() {
        const video = document.getElementById('cameraPreview');
        const canvas = document.getElementById('capturedCanvas');
        const context = canvas.getContext('2d');

        // Set canvas dimensions to match video stream
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

           // Capture current frame
           context.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Draw video frame on canvas
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Convert image to Base64
        capturedImage = canvas.toDataURL('image/png');

          // Show photo preview
          document.getElementById('photoPreview').src = capturedImage;
        document.getElementById('photoPreview').style.display = "block";

        // Hide video and capture button, show retake button
        video.style.display = "none";
        document.getElementById('captureBtn').style.display = "none";
        document.getElementById('retakeBtn').style.display = "inline-block";
        document.getElementById('submitClockIn').disabled = false; // Enable submit button
    }

     // Function to retake photo
     function retakePhoto() {
        document.getElementById('photoPreview').style.display = "none"; // Hide photo preview
        document.getElementById('cameraPreview').style.display = "block"; // Show camera again
        document.getElementById('captureBtn').style.display = "inline-block"; // Show capture button
        document.getElementById('retakeBtn').style.display = "none"; // Hide retake button
        document.getElementById('submitClockIn').disabled = true; // Disable submit button again
        capturedImage = null; // Reset captured image
    }

    // Function to send clock-in request
    function submitClockIn() {
    if (!capturedImage) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Please capture a photo before submitting.'
        });
        return;
    }

    // Get client time in proper format
    const now = new Date();
    const clientTime = now.getFullYear() + '-' +
                       ('0' + (now.getMonth() + 1)).slice(-2) + '-' +
                       ('0' + now.getDate()).slice(-2) + ' ' +
                       ('0' + now.getHours()).slice(-2) + ':' +
                       ('0' + now.getMinutes()).slice(-2) + ':' +
                       ('0' + now.getSeconds()).slice(-2);

    // Prepare form data
    const formData = new FormData();
    formData.append("client_time", clientTime);
    formData.append("photo", capturedImage);

    // Send AJAX request to PHP
    fetch("clock_in.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes("❌ Clock-in failed: Your system time is incorrect.")) {
            Swal.fire({
                icon: 'error',
                title: 'Time Mismatch!',
                text: data,
                confirmButtonText: 'OK'
            });
        } else if (data.includes("✅ Clock-in successful!")) {
            Swal.fire({
                icon: 'success',
                title: 'Clock-in Successful!',
                text: 'Your attendance has been recorded.',
                confirmButtonText: 'Okay'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Clock-in Failed',
                text: data
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Something went wrong: ' + error
        });
    });

    // Stop camera stream
    if (videoStream) {
        videoStream.getTracks().forEach(track => track.stop());
    }

    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('clockInModal'));
    modal.hide();
}



    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($show_alert): ?>
            Swal.fire({
                title: "Auto Clock-Out Applied",
                text: "An auto clock-out was applied for your last attendance. Please remember to clock out next time.",
                icon: "warning",
                confirmButtonText: "OK"
            });
        <?php endif; ?>
        updateAttendanceTable();
        autoClockOut();
        autoBreakOut();
    });

    function updateAttendanceTable() {
        fetch('fetch_attendance.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.querySelector('table tbody');
                tbody.innerHTML = '';

                data.forEach(row => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${row.date || 'N/A'}</td>
                        <td>${row.time_in || 'N/A'}</td>
                        <td>${row.time_out || 'N/A'}</td>
                        <td>${row.total_hours || 'N/A'}</td>
                        <td>${row.break_in || 'N/A'}</td>
                        <td>${row.break_out || 'N/A'}</td>
                        <td>${row.break_duration || '00:00:00'}</td>
                    `;
                    tbody.appendChild(tr);
                });
            })
            .catch(error => console.error('Error fetching attendance:', error));
    }

    async function clockAction(action) {
        const labels = {
            clock_out: 'Clock out',
            break_in: 'Break in',
            break_out: 'Break out'
        };
        const title = labels[action] || 'Attendance';
        const now = new Date();
        const options = {
            timeZone: 'Asia/Manila',
            year: 'numeric', month: '2-digit', day: '2-digit',
            hour: '2-digit', minute: '2-digit', second: '2-digit',
            hour12: false
        };

        let formatter = new Intl.DateTimeFormat('en-CA', options);
        let parts = formatter.formatToParts(now);
        let formattedTime = `${parts[0].value}-${parts[2].value}-${parts[4].value} ${parts[6].value}:${parts[8].value}:${parts[10].value}`;

        try {
            const response = await fetch(`${action}.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Accept': 'application/json'
                },
                body: 'client_time=' + encodeURIComponent(formattedTime)
            });
            const contentType = response.headers.get('content-type') || '';
            if (!contentType.includes('application/json')) {
                throw new Error('The attendance service returned an unexpected response.');
            }

            const result = await response.json();
            await Swal.fire({
                title: `${title} status`,
                text: result.message || 'The attendance action could not be completed.',
                icon: response.ok && result.success ? 'success' : 'error',
                confirmButtonText: 'OK'
            });

            if (response.ok && result.success) {
                updateAttendanceTable();
            }
        } catch (error) {
            console.error(`${title} failed:`, error);
            await Swal.fire({
                title: `${title} status`,
                text: 'The attendance service is unavailable. Please refresh the page and try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    }

function clockIn() { clockAction('clock_in'); }
function clockOut() { clockAction('clock_out'); }
function breakIn() { clockAction('break_in'); }
function breakOut() { clockAction('break_out'); }

function autoClockOut() {
    fetch('auto_clock_out.php', { method: 'POST' }).catch(() => undefined);
}

function autoBreakOut() {
    fetch('auto_break_out.php', { method: 'POST' }).catch(() => undefined);
}


// ✅ Define showNotification function
function showNotification(message) {
    Swal.fire({
        title: 'Notification',
        text: message,
        icon: message.includes("✅") ? 'success' : 'error',
        confirmButtonText: 'OK'
    }).then(() => {
        location.reload(); // Refresh the page to show new data
    });
}


</script>
</body>
</html>
