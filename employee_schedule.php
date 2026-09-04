<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/admin_session.php';
if (empty($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/helper.php';

$employeeResult = $conn->query(
    "SELECT e.id, e.firstname, e.lastname, e.employment_type, e.status,
            COALESCE(ws.scheduled_days, 0) AS scheduled_days,
            COALESCE(ws.weekly_hours, 0) AS weekly_hours,
            TIME_FORMAT(ws.earliest_start, '%h:%i %p') AS earliest_start,
            TIME_FORMAT(ws.latest_end, '%h:%i %p') AS latest_end
     FROM employees e
     LEFT JOIN (
        SELECT employee_id, COUNT(*) AS scheduled_days,
               SUM(required_hours) AS weekly_hours,
               MIN(start_time) AS earliest_start,
               MAX(end_time) AS latest_end
        FROM work_schedules
        GROUP BY employee_id
     ) ws ON ws.employee_id = e.id
     WHERE e.status = 'Active'
     ORDER BY e.lastname, e.firstname"
);

$employees = [];
$scheduledEmployees = 0;
$totalWeeklyHours = 0.0;
while ($employee = $employeeResult->fetch_assoc()) {
    $employee['scheduled_days'] = (int) $employee['scheduled_days'];
    $employee['weekly_hours'] = (float) $employee['weekly_hours'];
    if ($employee['scheduled_days'] > 0) {
        $scheduledEmployees++;
        $totalWeeklyHours += $employee['weekly_hours'];
    }
    $employees[] = $employee;
}
$activeEmployees = count($employees);
$unscheduledEmployees = $activeEmployees - $scheduledEmployees;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#8f102d">
    <link rel="icon" type="image/jpeg" href="images/asdasdasd123123123123123.jpg">
    <title>Work Schedules · HSSII Human Resources</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/schedule-builder.css?v=<?= rawurlencode((string) filemtime(__DIR__ . '/assets/css/schedule-builder.css')) ?>">
</head>
<body class="schedule-page">
    <div class="main-container"><?php include __DIR__ . '/sidebar.php'; ?></div>
    <div class="content-container"><?php include __DIR__ . '/nav_header.php'; ?></div>

    <main class="wrapper schedule-workspace" id="main-content">
        <section class="schedule-hero">
            <div>
                <span class="hshr-eyebrow"><i class="fa-solid fa-wand-magic-sparkles" aria-hidden="true"></i> Smart workforce planning</span>
                <h2>Build a teacher’s week in seconds.</h2>
                <p>Start from an editable preset, calculate paid hours automatically, and publish one conflict-free weekly schedule.</p>
            </div>
            <div class="schedule-timezone"><i class="fa-solid fa-earth-asia" aria-hidden="true"></i><span>Philippine Standard Time<strong>GMT+8 · Asia/Manila</strong></span></div>
        </section>

        <section class="schedule-stats" aria-label="Schedule overview">
            <article><span class="stat-icon stat-crimson"><i class="fa-solid fa-users" aria-hidden="true"></i></span><div><strong><?= $activeEmployees ?></strong><small>Active employees</small></div></article>
            <article><span class="stat-icon stat-green"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i></span><div><strong data-scheduled-count><?= $scheduledEmployees ?></strong><small>Schedules ready</small></div></article>
            <article><span class="stat-icon stat-amber"><i class="fa-solid fa-calendar-xmark" aria-hidden="true"></i></span><div><strong data-unscheduled-count><?= $unscheduledEmployees ?></strong><small>Need schedules</small></div></article>
            <article><span class="stat-icon stat-blue"><i class="fa-solid fa-clock" aria-hidden="true"></i></span><div><strong><?= number_format($totalWeeklyHours, 1) ?></strong><small>Planned hours/week</small></div></article>
        </section>

        <section class="schedule-panel">
            <div class="schedule-panel-header">
                <div>
                    <span class="hshr-eyebrow">Schedule directory</span>
                    <h3>Teaching and staff schedules</h3>
                </div>
                <div class="schedule-tools">
                    <label class="schedule-search">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                        <input type="search" placeholder="Search employees" data-schedule-search>
                    </label>
                    <select data-schedule-filter aria-label="Filter schedule status">
                        <option value="all">All employees</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="unscheduled">Needs schedule</option>
                    </select>
                </div>
            </div>

            <div class="schedule-table-wrap">
                <table class="schedule-table">
                    <thead>
                        <tr><th>Employee</th><th>Employment</th><th>Coverage</th><th>Weekly hours</th><th>Shift window</th><th><span class="sr-only">Actions</span></th></tr>
                    </thead>
                    <tbody data-employee-schedule-list>
                        <?php foreach ($employees as $employee): ?>
                            <?php
                            $hasSchedule = $employee['scheduled_days'] > 0;
                            $employeeName = trim($employee['firstname'] . ' ' . $employee['lastname']);
                            $initials = strtoupper(substr($employee['firstname'], 0, 1) . substr($employee['lastname'], 0, 1));
                            $employmentLabel = ucwords(str_replace(['_', '-'], ' ', (string) $employee['employment_type']));
                            ?>
                            <tr data-employee-row data-status="<?= $hasSchedule ? 'scheduled' : 'unscheduled' ?>" data-search="<?= htmlspecialchars(strtolower($employeeName . ' ' . $employee['id'] . ' ' . $employmentLabel)) ?>">
                                <td>
                                    <div class="schedule-person"><span><?= htmlspecialchars($initials) ?></span><div><strong><?= htmlspecialchars($employeeName) ?></strong><small><?= htmlspecialchars($employee['id']) ?></small></div></div>
                                </td>
                                <td><span class="employment-pill"><?= htmlspecialchars($employmentLabel ?: 'Not specified') ?></span></td>
                                <td data-schedule-coverage>
                                    <span class="coverage-pill <?= $hasSchedule ? 'is-ready' : 'needs-plan' ?>">
                                        <i class="fa-solid <?= $hasSchedule ? 'fa-circle-check' : 'fa-circle-exclamation' ?>" aria-hidden="true"></i>
                                        <?= $hasSchedule ? $employee['scheduled_days'] . ' days' : 'Not scheduled' ?>
                                    </span>
                                </td>
                                <td data-schedule-hours><?= $hasSchedule ? number_format($employee['weekly_hours'], 2) . ' hrs' : '—' ?></td>
                                <td data-schedule-window><?= $hasSchedule && $employee['earliest_start'] ? htmlspecialchars($employee['earliest_start'] . ' – ' . $employee['latest_end']) : '—' ?></td>
                                <td>
                                    <button class="schedule-build-button" type="button" data-build-schedule data-employee-id="<?= htmlspecialchars($employee['id']) ?>" data-employee-name="<?= htmlspecialchars($employeeName) ?>">
                                        <i class="fa-solid fa-wand-magic-sparkles" aria-hidden="true"></i> <?= $hasSchedule ? 'Edit plan' : 'Auto-build' ?>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="schedule-empty" data-schedule-empty hidden><i class="fa-regular fa-calendar-xmark" aria-hidden="true"></i><h3>No employees found</h3><p>Try another search or filter.</p></div>
            </div>
        </section>
    </main>

    <div class="modal fade schedule-builder-modal" id="scheduleBuilderModal" tabindex="-1" aria-labelledby="scheduleBuilderTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header schedule-modal-header">
                    <div>
                        <span class="hshr-eyebrow">Automated weekly planner</span>
                        <h2 class="modal-title" id="scheduleBuilderTitle">Create work schedule</h2>
                        <p data-builder-employee>Choose an employee to begin.</p>
                    </div>
                    <div class="modal-header-actions">
                        <span class="builder-timezone"><i class="fa-solid fa-clock" aria-hidden="true"></i> GMT+8</span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>

                <div class="modal-body schedule-modal-body">
                    <div class="builder-loading" data-builder-loading hidden><i class="fa-solid fa-circle-notch fa-spin" aria-hidden="true"></i><p>Loading schedule…</p></div>
                    <div data-builder-content>
                        <input type="hidden" data-builder-employee-id>
                        <div class="builder-alert" data-builder-alert hidden></div>

                        <section class="preset-section" aria-labelledby="presetTitle">
                            <div class="builder-section-heading"><div><span>Step 1</span><h3 id="presetTitle">Choose a smart starting point</h3></div><small>Every preset remains fully editable.</small></div>
                            <div class="preset-grid" data-preset-grid>
                                <button type="button" class="preset-card" data-preset="standard"><i class="fa-solid fa-school" aria-hidden="true"></i><span><strong>Standard teacher</strong><small>Mon–Fri · 7:30 AM–4:30 PM</small></span><b>40h</b></button>
                                <button type="button" class="preset-card" data-preset="early"><i class="fa-solid fa-sun" aria-hidden="true"></i><span><strong>Early teacher</strong><small>Mon–Fri · 6:30 AM–3:30 PM</small></span><b>40h</b></button>
                                <button type="button" class="preset-card" data-preset="morning"><i class="fa-solid fa-cloud-sun" aria-hidden="true"></i><span><strong>Morning part-time</strong><small>Mon–Fri · 7:30 AM–12:00 PM</small></span><b>22.5h</b></button>
                                <button type="button" class="preset-card" data-preset="compressed"><i class="fa-solid fa-bolt" aria-hidden="true"></i><span><strong>Four-day week</strong><small>Mon–Thu · 7:00 AM–5:30 PM</small></span><b>40h</b></button>
                            </div>
                        </section>

                        <section class="week-section" aria-labelledby="weekTitle">
                            <div class="builder-section-heading">
                                <div><span>Step 2</span><h3 id="weekTitle">Review the generated week</h3></div>
                                <button type="button" class="copy-monday-button" data-copy-monday><i class="fa-regular fa-copy" aria-hidden="true"></i> Copy Monday to active days</button>
                            </div>
                            <div class="week-header" aria-hidden="true"><span>Working day</span><span>Shift start</span><span>Shift end</span><span>Unpaid break</span><span>Paid hours</span></div>
                            <div class="week-grid" data-week-grid></div>
                        </section>
                    </div>
                </div>

                <div class="modal-footer schedule-modal-footer">
                    <button type="button" class="clear-schedule-button" data-clear-schedule hidden><i class="fa-regular fa-trash-can" aria-hidden="true"></i> Clear schedule</button>
                    <div class="builder-summary" aria-live="polite">
                        <span><small>Working days</small><strong data-summary-days>0</strong></span>
                        <span><small>Weekly paid hours</small><strong data-summary-hours>0.00</strong></span>
                        <span><small>Average per day</small><strong data-summary-average>0.00</strong></span>
                    </div>
                    <button type="button" class="save-schedule-button" data-save-schedule><i class="fa-solid fa-check" aria-hidden="true"></i> Publish weekly schedule</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/schedule-builder.js?v=<?= rawurlencode((string) filemtime(__DIR__ . '/assets/js/schedule-builder.js')) ?>" defer></script>
</body>
</html>
