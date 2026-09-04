<?php
require_once __DIR__ . '/includes/staff_session.php';

if (empty($_SESSION['employee_id'])) {
    http_response_code(401);
    exit;
}

$staffId = (string) ($_SESSION['employee_id'] ?? '');
$staffRole = 'staff';
$staffName = trim((string) (($staffData['firstname'] ?? '') . ' ' . ($staffData['lastname'] ?? '')));
if ($staffName === '') $staffName = (string) ($staffData['username'] ?? 'Staff member');

$staffPicture = hshr_profile_picture_url($staffData['profile_picture'] ?? null, '../');
$currentStaffPage = basename($_SERVER['PHP_SELF'] ?? 'dashboard.php');
$staffPageMap = [
    'dashboard.php' => ['Dashboard', 'Your personal HR workspace'],
    'staff_payroll.php' => ['Payroll', 'Review compensation and payslips'],
    'staff_attendance.php' => ['Attendance', 'Track your workday and history'],
    'staff_leave_requests.php' => ['Leave', 'Manage requests and balances'],
    'file_reports.php' => ['Reports', 'Submit an incident or concern'],
    'incident_report.php' => ['Incident report', 'Document a workplace incident'],
    'staff_settings.php' => ['Settings', 'Manage your account preferences'],
    'account_settings.php' => ['Account', 'Update sign-in information'],
    'staff_viewprofile.php' => ['My profile', 'Review your employee information'],
];
[$staffPageTitle, $staffPageSubtitle] = $staffPageMap[$currentStaffPage] ?? ['Employee portal', 'Holy Spirit School of Imus, Inc.'];

$staffContacts = [];
$staffUnread = 0;
if ($staffId !== '' && isset($conn) && $conn instanceof mysqli) {
    $unread = $conn->prepare("SELECT COUNT(*) FROM messages WHERE receiver_id = ? AND receiver_type = 'staff' AND status <> 'seen'");
    if ($unread) {
        $unread->bind_param('s', $staffId);
        $unread->execute();
        $unread->bind_result($staffUnread);
        $unread->fetch();
        $unread->close();
    }

    $contacts = $conn->query(
        "SELECT contact_id, username, display_name, profile_picture, contact_role
         FROM (
            SELECT CAST(id AS CHAR) AS contact_id, username, name AS display_name,
                   profile_picture, 'Administrator' AS contact_role
            FROM admin
            UNION ALL
            SELECT sa.employee_id, sa.username,
                   TRIM(CONCAT(COALESCE(e.firstname, ''), ' ', COALESCE(e.lastname, ''))),
                   sa.profile_picture, 'staff'
            FROM staff_accounts sa
            LEFT JOIN employees e ON e.id = sa.employee_id
            WHERE sa.status = 'active'
         ) people
         ORDER BY display_name, username"
    );
    if ($contacts) {
        while ($contact = $contacts->fetch_assoc()) {
            if ((string) $contact['contact_id'] === $staffId && $contact['contact_role'] === 'staff') continue;
            $staffContacts[] = $contact;
        }
    }
}

$staffNavigation = [
    ['dashboard.php', 'fa-solid fa-house', 'Dashboard'],
    ['staff_attendance.php', 'fa-regular fa-clock', 'Attendance'],
    ['staff_payroll.php', 'fa-solid fa-wallet', 'Payroll'],
    ['staff_leave_requests.php', 'fa-regular fa-calendar-check', 'Leave'],
    ['file_reports.php', 'fa-regular fa-file-lines', 'Reports'],
];
?>
<link rel="stylesheet" href="../assets/css/staff-next.css?v=20260825-1">
<script>document.body.classList.add('hshr-staff-app'<?= !empty($_SESSION['dark_mode']) ? ", 'hshr-staff-dark'" : '' ?>);</script>

<header class="hshr-staff-shell" data-staff-shell>
    <div class="hshr-staff-topbar">
        <a class="hshr-staff-brand" href="dashboard.php" aria-label="Employee portal home">
            <img src="../images/asdasdasd123123123123123.jpg" alt="">
            <span><strong>Holy Spirit School of Imus</strong><small>Employee portal</small></span>
        </a>
        <div class="hshr-staff-context"><small><?= htmlspecialchars($staffPageSubtitle) ?></small><strong><?= htmlspecialchars($staffPageTitle) ?></strong></div>
        <div class="hshr-staff-actions">
            <button type="button" class="hshr-staff-icon-button" data-staff-theme aria-label="Toggle appearance"><i class="fa-regular fa-moon"></i></button>
            <button type="button" class="hshr-staff-icon-button" data-staff-notifications aria-label="Notifications"><i class="fa-regular fa-bell"></i></button>
            <button type="button" class="hshr-staff-icon-button" data-staff-messages-open aria-label="Messages">
                <i class="fa-regular fa-envelope"></i>
                <?php if ($staffUnread > 0): ?><span><?= $staffUnread > 99 ? '99+' : (int) $staffUnread ?></span><?php endif; ?>
            </button>
            <div class="hshr-staff-profile-menu">
                <button type="button" data-staff-profile-toggle aria-expanded="false">
                    <img src="<?= htmlspecialchars($staffPicture) ?>" alt="<?= htmlspecialchars($staffName) ?>" onerror="this.onerror=null;this.src='../images/image-not-found.jpg'">
                    <span><strong><?= htmlspecialchars($staffName) ?></strong><small>Staff member</small></span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="hshr-staff-popover" data-staff-profile-panel hidden>
                    <a href="staff_viewprofile.php"><i class="fa-regular fa-user"></i> My profile</a>
                    <a href="staff_settings.php"><i class="fa-solid fa-sliders"></i> Settings</a>
                    <hr>
                    <a class="danger" href="staff_logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Sign out</a>
                </div>
            </div>
        </div>
    </div>
    <nav class="hshr-staff-nav" aria-label="Employee portal navigation">
        <?php foreach ($staffNavigation as [$href, $icon, $label]): ?>
            <a href="<?= $href ?>"<?= $currentStaffPage === $href ? ' class="active" aria-current="page"' : '' ?>><i class="<?= $icon ?>"></i><span><?= $label ?></span></a>
        <?php endforeach; ?>
    </nav>
</header>

<div class="hshr-staff-toast" data-staff-toast hidden><i class="fa-regular fa-circle-check"></i><span>You are all caught up.</span></div>
<div class="hshr-staff-message-overlay" data-staff-message-overlay hidden></div>
<aside class="hshr-staff-message-center" data-staff-message-center data-sender-id="<?= htmlspecialchars($staffId) ?>" aria-hidden="true">
    <header>
        <div><small>Communication</small><h2>Messages</h2></div>
        <button type="button" class="hshr-staff-icon-button" data-staff-messages-close aria-label="Close messages"><i class="fa-solid fa-xmark"></i></button>
    </header>
    <div class="hshr-staff-message-layout">
        <section class="hshr-staff-contact-panel">
            <label><i class="fa-solid fa-magnifying-glass"></i><input type="search" data-staff-contact-search placeholder="Search people"></label>
            <div class="hshr-staff-contacts">
                <?php foreach ($staffContacts as $contact): ?>
                    <?php $name = trim((string) $contact['display_name']) ?: (string) $contact['username']; ?>
                    <button type="button" data-staff-contact data-id="<?= htmlspecialchars((string) $contact['contact_id']) ?>" data-role="<?= htmlspecialchars((string) $contact['contact_role']) ?>" data-search="<?= htmlspecialchars(strtolower($name . ' ' . $contact['username'])) ?>">
                        <img src="<?= htmlspecialchars(hshr_profile_picture_url($contact['profile_picture'] ?? null, '../')) ?>" alt="" onerror="this.onerror=null;this.src='../images/image-not-found.jpg'">
                        <span><strong><?= htmlspecialchars($name) ?></strong><small><?= htmlspecialchars((string) $contact['contact_role']) ?></small></span>
                    </button>
                <?php endforeach; ?>
            </div>
        </section>
        <section class="hshr-staff-conversation">
            <div class="hshr-staff-conversation-title"><strong data-staff-conversation-name>Select a person</strong><small data-staff-conversation-role>Choose someone to start a conversation.</small></div>
            <div class="hshr-staff-chat" data-staff-chat><div class="hshr-staff-empty"><i class="fa-regular fa-comments"></i><p>Your conversations will appear here.</p></div></div>
            <form data-staff-message-form><input type="text" data-staff-message-input placeholder="Write a message…" maxlength="4000" disabled><button type="submit" disabled aria-label="Send message"><i class="fa-solid fa-paper-plane"></i></button></form>
        </section>
    </div>
</aside>
<script>document.documentElement.dataset.hshrCsrfToken = <?= json_encode(hshr_csrf_token(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;</script>
<script defer src="../assets/js/staff-next.js?v=20260825-1"></script>
