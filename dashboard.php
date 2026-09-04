<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/admin_session.php';

if (empty($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

date_default_timezone_set('Asia/Manila');
require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/helper.php';

$metrics = [
    'total_staff' => 0,
    'active_staff' => 0,
    'active_accounts' => 0,
    'pending_leave' => 0,
    'pending_attendance' => 0,
    'checked_in_today' => 0,
];

$metricsResult = $conn->query(
    "SELECT
        (SELECT COUNT(*) FROM employees) AS total_staff,
        (SELECT COUNT(*) FROM employees WHERE status = 'Active') AS active_staff,
        (SELECT COUNT(*) FROM staff_accounts WHERE status = 'active') AS active_accounts,
        (SELECT COUNT(*) FROM leave_requests WHERE status = 'Pending') AS pending_leave,
        (SELECT COUNT(*) FROM attendance WHERE status = 'Pending') AS pending_attendance,
        (SELECT COUNT(*) FROM attendance WHERE date = CURDATE() AND time_in IS NOT NULL) AS checked_in_today"
);
if ($metricsResult) {
    $metrics = array_map('intval', $metricsResult->fetch_assoc());
}

$today = date('Y-m-d');
$historyStatement = $conn->prepare(
    'INSERT INTO historical_data (date, total_staff, active_teachers)
     VALUES (?, ?, ?)
     ON DUPLICATE KEY UPDATE total_staff = VALUES(total_staff), active_teachers = VALUES(active_teachers)'
);
$historyStatement->bind_param('sii', $today, $metrics['total_staff'], $metrics['active_accounts']);
$historyStatement->execute();
$historyStatement->close();

$yesterday = date('Y-m-d', strtotime('-1 day'));
$yesterdayStaff = 0;
$yesterdayAccounts = 0;
$previousStatement = $conn->prepare('SELECT total_staff, active_teachers FROM historical_data WHERE date = ?');
$previousStatement->bind_param('s', $yesterday);
$previousStatement->execute();
$previous = $previousStatement->get_result()->fetch_assoc();
if ($previous) {
    $yesterdayStaff = (int) $previous['total_staff'];
    $yesterdayAccounts = (int) $previous['active_teachers'];
}
$previousStatement->close();

$staffDelta = $metrics['total_staff'] - $yesterdayStaff;
$accountDelta = $metrics['active_accounts'] - $yesterdayAccounts;
$attendanceRate = $metrics['active_staff'] > 0
    ? min(100, (int) round(($metrics['checked_in_today'] / $metrics['active_staff']) * 100))
    : 0;

$history = [];
$historyResult = $conn->query(
    'SELECT date, total_staff, active_teachers
     FROM historical_data
     WHERE date >= DATE_SUB(CURDATE(), INTERVAL 13 DAY)
     ORDER BY date ASC'
);
if ($historyResult) {
    while ($row = $historyResult->fetch_assoc()) {
        $history[] = [
            'date' => $row['date'],
            'total_staff' => (int) $row['total_staff'],
            'active_teachers' => (int) $row['active_teachers'],
        ];
    }
}

$pendingLeaves = [];
$leaveResult = $conn->query(
    "SELECT l.leave_id, CONCAT(e.firstname, ' ', e.lastname) AS employee_name,
            lt.leave_name, l.leave_start_date, l.leave_end_date, l.total_days, l.request_date
     FROM leave_requests l
     JOIN employees e ON e.id = l.employee_id
     JOIN leave_types lt ON lt.leave_type_id = l.leave_type_id
     WHERE l.status = 'Pending'
     ORDER BY l.request_date DESC
     LIMIT 6"
);
if ($leaveResult) {
    while ($row = $leaveResult->fetch_assoc()) {
        $pendingLeaves[] = $row;
    }
}

function trendLabel(int $delta): string
{
    if ($delta > 0) return '+' . $delta . ' since yesterday';
    if ($delta < 0) return $delta . ' since yesterday';
    return 'No change since yesterday';
}

function trendClass(int $delta): string
{
    if ($delta > 0) return 'positive';
    if ($delta < 0) return 'negative';
    return 'neutral';
}

function chartPoints(array $history, string $field): string
{
    if (!$history) return '0,72 100,72';
    $values = array_column($history, $field);
    $min = min($values);
    $max = max($values);
    $range = max(1, $max - $min);
    $lastIndex = max(1, count($values) - 1);
    $points = [];
    foreach ($values as $index => $value) {
        $x = ($index / $lastIndex) * 100;
        $y = 78 - ((($value - $min) / $range) * 56);
        $points[] = number_format($x, 2, '.', '') . ',' . number_format($y, 2, '.', '');
    }
    return implode(' ', $points);
}

$displayName = trim((string) ($userData['name'] ?? 'Administrator'));
$position = trim((string) ($userData['position'] ?? 'Administrator'));
$profilePicture = hshr_profile_picture_url($userData['profile_picture'] ?? null);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#8f102d">
    <link rel="icon" type="image/png" href="images/asdasdasd123123123123123.jpg">
    <title>Dashboard · HSSII Human Resources</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
</head>
<body class="admin-dashboard-page">
    <div class="main-container"><?php include __DIR__ . '/sidebar.php'; ?></div>
    <div class="content-container"><?php include __DIR__ . '/nav_header.php'; ?></div>

    <main class="wrapper hshr-dashboard" id="main-content">
        <section class="hshr-dashboard-hero" aria-labelledby="welcomeTitle">
            <div class="hshr-hero-copy">
                <span class="hshr-eyebrow"><i class="fa-solid fa-wand-magic-sparkles" aria-hidden="true"></i> People operations workspace</span>
                <h2 id="welcomeTitle"><span data-dashboard-greeting>Welcome back</span>, <?= htmlspecialchars($displayName) ?></h2>
                <p>Keep your workforce, attendance, payroll, and employee requests moving from one focused view.</p>
                <div class="hshr-hero-actions">
                    <a class="hshr-button hshr-button-primary" href="employees.php"><i class="fa-solid fa-users" aria-hidden="true"></i> View employees</a>
                    <a class="hshr-button hshr-button-secondary" href="employee_attendance.php"><i class="fa-regular fa-clock" aria-hidden="true"></i> Review attendance</a>
                </div>
            </div>
            <div class="hshr-hero-profile">
                <img src="<?= htmlspecialchars($profilePicture) ?>" alt="<?= htmlspecialchars($displayName) ?>" onerror="this.onerror=null;this.src='images/image-not-found.jpg'">
                <span class="hshr-online-dot" aria-label="Online"></span>
                <div>
                    <strong><?= htmlspecialchars($displayName) ?></strong>
                    <small><?= htmlspecialchars($position) ?></small>
                    <time data-dashboard-time datetime="<?= date(DATE_ATOM) ?>"><?= date('g:i A') ?></time>
                </div>
            </div>
        </section>

        <section class="hshr-section" aria-labelledby="workforceOverview">
            <div class="hshr-section-heading">
                <div><span class="hshr-eyebrow">Live overview</span><h2 id="workforceOverview">Your workforce today</h2></div>
                <span class="hshr-updated-label"><i class="fa-regular fa-circle-check"></i> Updated <?= date('g:i A') ?></span>
            </div>
            <div class="hshr-kpi-grid">
                <article class="hshr-kpi-card hshr-accent-crimson">
                    <div class="hshr-kpi-icon"><i class="fa-solid fa-users"></i></div>
                    <span>Total staff</span><strong><?= $metrics['total_staff'] ?></strong>
                    <small class="<?= trendClass($staffDelta) ?>"><i class="fa-solid fa-arrow-trend-up"></i> <?= htmlspecialchars(trendLabel($staffDelta)) ?></small>
                </article>
                <article class="hshr-kpi-card hshr-accent-green">
                    <div class="hshr-kpi-icon"><i class="fa-solid fa-user-check"></i></div>
                    <span>Active accounts</span><strong><?= $metrics['active_accounts'] ?></strong>
                    <small class="<?= trendClass($accountDelta) ?>"><i class="fa-solid fa-shield-halved"></i> <?= htmlspecialchars(trendLabel($accountDelta)) ?></small>
                </article>
                <article class="hshr-kpi-card hshr-accent-amber">
                    <div class="hshr-kpi-icon"><i class="fa-solid fa-calendar-day"></i></div>
                    <span>Leave requests</span><strong><?= $metrics['pending_leave'] ?></strong>
                    <small class="neutral">Waiting for review</small>
                </article>
                <article class="hshr-kpi-card hshr-accent-blue">
                    <div class="hshr-kpi-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    <span>Attendance queue</span><strong><?= $metrics['pending_attendance'] ?></strong>
                    <small class="neutral">Pending records</small>
                </article>
            </div>
        </section>

        <section class="hshr-dashboard-grid" aria-label="Workforce insights">
            <article class="hshr-panel hshr-trend-panel">
                <div class="hshr-panel-heading">
                    <div><span class="hshr-eyebrow">14-day movement</span><h2>Workforce trend</h2></div>
                    <div class="hshr-chart-legend"><span class="staff">Staff</span><span class="accounts">Accounts</span></div>
                </div>
                <div class="hshr-line-chart" role="img" aria-label="Trend of total staff and active staff accounts over the last fourteen days">
                    <svg viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                        <line x1="0" y1="22" x2="100" y2="22"></line><line x1="0" y1="50" x2="100" y2="50"></line><line x1="0" y1="78" x2="100" y2="78"></line>
                        <polyline class="accounts-line" points="<?= htmlspecialchars(chartPoints($history, 'active_teachers')) ?>"></polyline>
                        <polyline class="staff-line" points="<?= htmlspecialchars(chartPoints($history, 'total_staff')) ?>"></polyline>
                    </svg>
                </div>
                <div class="hshr-chart-axis"><span><?= $history ? date('M j', strtotime($history[0]['date'])) : date('M j') ?></span><span>Today</span></div>
            </article>

            <article class="hshr-panel hshr-attendance-panel">
                <div class="hshr-panel-heading"><div><span class="hshr-eyebrow">Daily participation</span><h2>Attendance pulse</h2></div></div>
                <div class="hshr-progress-ring" style="--progress: <?= $attendanceRate ?>" aria-label="<?= $attendanceRate ?> percent attendance today"><strong><?= $attendanceRate ?>%</strong><span>today</span></div>
                <div class="hshr-attendance-stats">
                    <div><strong><?= $metrics['checked_in_today'] ?></strong><span>Checked in</span></div>
                    <div><strong><?= max(0, $metrics['active_staff'] - $metrics['checked_in_today']) ?></strong><span>Not recorded</span></div>
                </div>
                <a class="hshr-text-link" href="employee_attendance.php">Open attendance <i class="fa-solid fa-arrow-right"></i></a>
            </article>
        </section>

        <section class="hshr-dashboard-grid hshr-dashboard-lower" aria-label="Pending work and shortcuts">
            <article class="hshr-panel hshr-requests-panel">
                <div class="hshr-panel-heading">
                    <div><span class="hshr-eyebrow">Needs attention</span><h2>Pending leave requests</h2></div>
                    <a class="hshr-text-link" href="leave_requests.php">View all <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <?php if (!$pendingLeaves): ?>
                    <div class="hshr-empty-state hshr-dashboard-empty"><i class="fa-regular fa-circle-check"></i><h3>Nothing waiting</h3><p>New leave requests will appear here.</p></div>
                <?php else: ?>
                    <div class="hshr-request-list">
                        <?php foreach ($pendingLeaves as $leave): ?>
                            <a href="leave_requests.php" class="hshr-request-row">
                                <span class="hshr-request-avatar"><?= htmlspecialchars(strtoupper(substr($leave['employee_name'], 0, 1))) ?></span>
                                <span class="hshr-request-person"><strong><?= htmlspecialchars($leave['employee_name']) ?></strong><small><?= htmlspecialchars($leave['leave_name']) ?> · <?= (int) $leave['total_days'] ?> day<?= (int) $leave['total_days'] === 1 ? '' : 's' ?></small></span>
                                <span class="hshr-request-date"><?= htmlspecialchars(date('M j', strtotime($leave['leave_start_date']))) ?><small>start date</small></span>
                                <span class="hshr-status-pill pending">Pending</span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </article>

            <article class="hshr-panel hshr-shortcuts-panel">
                <div class="hshr-panel-heading"><div><span class="hshr-eyebrow">Quick access</span><h2>Common workflows</h2></div></div>
                <div class="hshr-shortcut-grid">
                    <a href="employees.php"><span><i class="fa-solid fa-user-plus"></i></span><strong>Employee records</strong><small>Add or update staff</small></a>
                    <a href="payroll.php"><span><i class="fa-solid fa-wallet"></i></span><strong>Payroll</strong><small>Review compensation</small></a>
                    <a href="employee_schedule.php"><span><i class="fa-regular fa-calendar"></i></span><strong>Schedules</strong><small>Plan work hours</small></a>
                    <a href="employment_applicants_list.php"><span><i class="fa-solid fa-user-tie"></i></span><strong>Applicants</strong><small>Manage candidates</small></a>
                </div>
            </article>
        </section>
    </main>
    <script defer src="assets/js/admin-dashboard.js?v=20260825-1"></script>
</body>
</html>
