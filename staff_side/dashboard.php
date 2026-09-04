<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/staff_session.php';

if (empty($_SESSION['employee_id']) || !in_array(strtolower((string) ($_SESSION['role'] ?? '')), ['staff', 'intern'], true)) {
    header('Location: index.php');
    exit;
}

date_default_timezone_set('Asia/Manila');
require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/staff_helper.php';

$employeeId = (string) $_SESSION['employee_id'];
$staffData = getStaffData($employeeId);
if (!$staffData) {
    session_destroy();
    header('Location: index.php');
    exit;
}

$todayAttendance = null;
$attendance = $conn->prepare(
    'SELECT time_in, break_in, break_out, time_out, total_hours, status
     FROM attendance WHERE employee_id = ? AND date = CURDATE() LIMIT 1'
);
$attendance->bind_param('s', $employeeId);
$attendance->execute();
$todayAttendance = $attendance->get_result()->fetch_assoc();
$attendance->close();

$pendingLeave = 0;
$approvedLeave = 0;
$leaveSummary = $conn->prepare(
    "SELECT
        SUM(status = 'Pending') AS pending_count,
        SUM(status = 'Approved') AS approved_count
     FROM leave_requests WHERE employee_id = ?"
);
$leaveSummary->bind_param('s', $employeeId);
$leaveSummary->execute();
$leave = $leaveSummary->get_result()->fetch_assoc();
$pendingLeave = (int) ($leave['pending_count'] ?? 0);
$approvedLeave = (int) ($leave['approved_count'] ?? 0);
$leaveSummary->close();

$recentAttendance = [];
$recent = $conn->prepare(
    'SELECT date, time_in, time_out, total_hours, status
     FROM attendance WHERE employee_id = ? ORDER BY date DESC LIMIT 5'
);
$recent->bind_param('s', $employeeId);
$recent->execute();
$recentResult = $recent->get_result();
while ($row = $recentResult->fetch_assoc()) $recentAttendance[] = $row;
$recent->close();

$requiredHours = 0.0;
$schedule = $conn->prepare('SELECT COALESCE(SUM(required_hours), 0) FROM work_schedules WHERE employee_id = ?');
$schedule->bind_param('s', $employeeId);
$schedule->execute();
$schedule->bind_result($requiredHours);
$schedule->fetch();
$schedule->close();

$firstName = trim((string) ($staffData['firstname'] ?? 'Staff'));
$fullName = trim($firstName . ' ' . (string) ($staffData['lastname'] ?? ''));
$profilePicture = hshr_profile_picture_url($staffData['profile_picture'] ?? null, '../');
$attendanceState = $todayAttendance ? 'Day in progress' : 'Not clocked in';
if (!empty($todayAttendance['time_out'])) $attendanceState = 'Workday complete';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#9c1738">
    <link rel="icon" type="image/png" href="../images/asdasdasd123123123123123.jpg">
    <title>Employee Dashboard · HSSII</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/staff_navbar.php'; ?>

    <main class="hshr-staff-dashboard" id="main-content">
        <section class="hshr-staff-hero">
            <div>
                <span class="hshr-staff-eyebrow"><i class="fa-solid fa-wand-magic-sparkles"></i> Personal workspace</span>
                <h1><span data-staff-greeting>Hello</span>, <?= htmlspecialchars($firstName) ?>.</h1>
                <p>Everything you need for your workday—attendance, payslips, leave, and requests—in one calm, focused place.</p>
                <div class="hshr-staff-hero-actions">
                    <a href="staff_attendance.php" class="primary"><i class="fa-regular fa-clock"></i> Open attendance</a>
                    <a href="staff_leave_requests.php"><i class="fa-regular fa-calendar-plus"></i> Request leave</a>
                </div>
            </div>
            <article class="hshr-staff-identity-card">
                <img src="<?= htmlspecialchars($profilePicture) ?>" alt="<?= htmlspecialchars($fullName) ?>" onerror="this.onerror=null;this.src='../images/image-not-found.jpg'">
                <div><strong><?= htmlspecialchars($fullName) ?></strong><small><?= htmlspecialchars((string) ($staffData['work_occupation'] ?? 'Staff member')) ?></small><span><?= htmlspecialchars($employeeId) ?></span></div>
            </article>
        </section>

        <section class="hshr-staff-day-grid" aria-label="Workday summary">
            <article class="hshr-staff-day-card featured">
                <header><span>Today’s attendance</span><i class="fa-regular fa-clock"></i></header>
                <strong><?= htmlspecialchars($attendanceState) ?></strong>
                <div class="hshr-staff-time-grid">
                    <span><small>Time in</small><b><?= !empty($todayAttendance['time_in']) ? date('g:i A', strtotime($todayAttendance['time_in'])) : '—' ?></b></span>
                    <span><small>Time out</small><b><?= !empty($todayAttendance['time_out']) ? date('g:i A', strtotime($todayAttendance['time_out'])) : '—' ?></b></span>
                </div>
                <a href="staff_attendance.php">Manage workday <i class="fa-solid fa-arrow-right"></i></a>
            </article>
            <article class="hshr-staff-day-card"><header><span>Monthly salary</span><i class="fa-solid fa-wallet"></i></header><strong>₱<?= number_format((float) ($staffData['salary'] ?? 0), 2) ?></strong><small>Current employee record</small><a href="staff_payroll.php">View payroll <i class="fa-solid fa-arrow-right"></i></a></article>
            <article class="hshr-staff-day-card"><header><span>Leave requests</span><i class="fa-regular fa-calendar-check"></i></header><strong><?= $pendingLeave ?> pending</strong><small><?= $approvedLeave ?> approved request<?= $approvedLeave === 1 ? '' : 's' ?></small><a href="staff_leave_requests.php">Review leave <i class="fa-solid fa-arrow-right"></i></a></article>
            <article class="hshr-staff-day-card"><header><span>Weekly schedule</span><i class="fa-solid fa-business-time"></i></header><strong><?= number_format((float) $requiredHours, 1) ?> hours</strong><small>Required work hours</small><a href="staff_attendance.php">See history <i class="fa-solid fa-arrow-right"></i></a></article>
        </section>

        <section class="hshr-staff-content-grid">
            <article class="hshr-staff-panel">
                <header><div><span class="hshr-staff-eyebrow">Recent activity</span><h2>Attendance history</h2></div><a href="staff_attendance.php">View all <i class="fa-solid fa-arrow-right"></i></a></header>
                <?php if (!$recentAttendance): ?>
                    <div class="hshr-staff-panel-empty"><i class="fa-regular fa-clock"></i><strong>No attendance records yet</strong><span>Your recent workdays will appear here.</span></div>
                <?php else: ?>
                    <div class="hshr-staff-attendance-list">
                        <?php foreach ($recentAttendance as $record): ?>
                            <div>
                                <span class="date"><strong><?= date('D', strtotime($record['date'])) ?></strong><small><?= date('M j', strtotime($record['date'])) ?></small></span>
                                <span><small>Time in</small><strong><?= $record['time_in'] ? date('g:i A', strtotime($record['time_in'])) : '—' ?></strong></span>
                                <span><small>Time out</small><strong><?= $record['time_out'] ? date('g:i A', strtotime($record['time_out'])) : '—' ?></strong></span>
                                <span class="status"><?= htmlspecialchars((string) ($record['status'] ?: 'Recorded')) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </article>

            <article class="hshr-staff-panel hshr-staff-shortcuts">
                <header><div><span class="hshr-staff-eyebrow">Quick access</span><h2>Employee services</h2></div></header>
                <div>
                    <a href="staff_payroll.php"><span><i class="fa-solid fa-file-invoice-dollar"></i></span><strong>Payslips</strong><small>Compensation history</small></a>
                    <a href="file_reports.php"><span><i class="fa-regular fa-file-lines"></i></span><strong>File a report</strong><small>Raise a concern</small></a>
                    <a href="staff_viewprofile.php"><span><i class="fa-regular fa-id-card"></i></span><strong>My profile</strong><small>Employee information</small></a>
                    <a href="staff_settings.php"><span><i class="fa-solid fa-sliders"></i></span><strong>Settings</strong><small>Account preferences</small></a>
                </div>
            </article>
        </section>
    </main>
    <script defer src="../assets/js/staff-dashboard.js?v=20260825-1"></script>
</body>
</html>
