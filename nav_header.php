<?php
require_once __DIR__ . '/includes/admin_page.php';
require_once __DIR__ . '/db_config.php';

$adminId = $_SESSION['admin_id'] ?? null;
$adminRole = $_SESSION['position'] ?? 'Administrator';
$profilePicture = hshr_profile_picture_url($userData['profile_picture'] ?? null);
$displayName = $userData['name'] ?? 'Administrator';

$pageTitles = [
    'dashboard.php' => ['Dashboard', 'A concise view of your people operations'],
    'employees.php' => ['Employee directory', 'Manage employee records and employment status'],
    'staff_accounts.php' => ['Staff accounts', 'Manage access to the employee portal'],
    'employee_details.php' => ['Employee profile', 'Review complete employee information'],
    'employee_schedule.php' => ['Work schedules', 'Plan and maintain employee schedules'],
    'role_management.php' => ['Roles & access', 'Control permissions and responsibility levels'],
    'employment_applicants_list.php' => ['Applicants', 'Review and process employment applications'],
    'payroll.php' => ['Payroll', 'Prepare and review employee compensation'],
    'deductions.php' => ['Deductions', 'Manage payroll deductions and contribution rules'],
    'leave_requests.php' => ['Leave requests', 'Review employee leave submissions'],
    'employee_attendance.php' => ['Attendance', 'Monitor daily time and attendance records'],
    'settings.php' => ['Settings', 'Configure your HR workspace'],
    'view_profile.php' => ['My profile', 'Manage your administrator profile'],
];

$currentPage = basename($_SERVER['PHP_SELF']);
[$pageTitle, $pageSubtitle] = $pageTitles[$currentPage] ?? ['Human Resources', 'Holy Spirit School of Imus, Inc.'];

$notificationCount = 0;
$unreadMessageCount = 0;
$notifications = [];
$messageContacts = [];

if ($adminId !== null && isset($conn) && $conn instanceof mysqli) {
    $notificationResult = $conn->query(
        "SELECT
            (SELECT COUNT(*) FROM attendance WHERE status = 'Pending') +
            (SELECT COUNT(*) FROM leave_requests WHERE status = 'Pending') AS total"
    );
    if ($notificationResult) {
        $notificationCount = (int) ($notificationResult->fetch_assoc()['total'] ?? 0);
    }

    $notificationItems = $conn->query(
        "(SELECT a.id, CONCAT(e.firstname, ' ', e.lastname) AS employee_name,
                a.time_in AS event_time, 'Attendance approval' AS event_label,
                'employee_attendance.php' AS event_url, 'fa-solid fa-clock' AS event_icon
         FROM attendance a
         JOIN employees e ON e.id = a.employee_id
         WHERE a.status = 'Pending')
         UNION ALL
         (SELECT l.leave_id AS id, CONCAT(e.firstname, ' ', e.lastname) AS employee_name,
                l.request_date AS event_time, 'Leave request' AS event_label,
                'leave_requests.php' AS event_url, 'fa-solid fa-calendar-day' AS event_icon
         FROM leave_requests l
         JOIN employees e ON e.id = l.employee_id
         WHERE l.status = 'Pending')
         ORDER BY event_time DESC
         LIMIT 6"
    );
    if ($notificationItems) {
        while ($notification = $notificationItems->fetch_assoc()) {
            $notifications[] = $notification;
        }
    }

    $unreadStatement = $conn->prepare("SELECT COUNT(*) FROM messages WHERE receiver_id = ? AND receiver_type = 'Administrator' AND status <> 'seen'");
    if ($unreadStatement) {
        $adminIdString = (string) $adminId;
        $unreadStatement->bind_param('s', $adminIdString);
        $unreadStatement->execute();
        $unreadStatement->bind_result($unreadMessageCount);
        $unreadStatement->fetch();
        $unreadStatement->close();
        $unreadMessageCount = (int) $unreadMessageCount;
    }

    $contactResult = $conn->query(
        "SELECT contact_id, username, display_name, profile_picture, contact_role
         FROM (
            SELECT CAST(id AS CHAR) AS contact_id, username, name AS display_name,
                   profile_picture, 'Administrator' AS contact_role
            FROM admin
            UNION ALL
            SELECT sa.employee_id AS contact_id, sa.username,
                   TRIM(CONCAT(COALESCE(e.firstname, ''), ' ', COALESCE(e.lastname, ''))) AS display_name,
                   sa.profile_picture, 'staff' AS contact_role
            FROM staff_accounts sa
            LEFT JOIN employees e ON e.id = sa.employee_id
            WHERE sa.status = 'active'
         ) contacts
         ORDER BY display_name, username"
    );
    if ($contactResult) {
        while ($contact = $contactResult->fetch_assoc()) {
            if ((string) $contact['contact_id'] === (string) $adminId && strtolower($contact['contact_role']) === strtolower($adminRole)) {
                continue;
            }
            $messageContacts[] = $contact;
        }
    }
}
?>

<header class="navbar hshr-topbar" id="topbar">
    <div class="hshr-topbar-leading">
        <button class="hshr-icon-button hshr-mobile-nav-toggle" id="mobileSidebarToggle" type="button" aria-controls="sidebar" aria-expanded="false" aria-label="Open navigation">
            <i class="fa-solid fa-bars" aria-hidden="true"></i>
        </button>
        <div class="hshr-page-context">
            <p><?= htmlspecialchars($pageSubtitle) ?></p>
            <h1><?= htmlspecialchars($pageTitle) ?></h1>
        </div>
    </div>

    <div class="hshr-topbar-actions">
        <button class="hshr-icon-button" id="darkModeToggle" type="button" aria-label="Toggle dark mode" title="Toggle appearance">
            <i id="darkModeIcon" class="fa-solid <?= !empty($userData['darkmodeOn']) ? 'fa-sun' : 'fa-moon' ?>" aria-hidden="true"></i>
        </button>

        <div class="hshr-menu" data-hshr-menu="notifications">
            <button class="hshr-icon-button" type="button" data-hshr-menu-toggle aria-expanded="false" aria-label="Notifications" title="Notifications">
                <i class="fa-regular fa-bell" aria-hidden="true"></i>
                <?php if ($notificationCount > 0): ?>
                    <span class="hshr-action-badge"><?= $notificationCount > 99 ? '99+' : $notificationCount ?></span>
                <?php endif; ?>
            </button>
            <div class="hshr-popover hshr-notification-popover" data-hshr-menu-panel hidden>
                <div class="hshr-popover-header">
                    <div>
                        <strong>Notifications</strong>
                        <span><?= $notificationCount ?> pending item<?= $notificationCount === 1 ? '' : 's' ?></span>
                    </div>
                </div>
                <div class="hshr-popover-list">
                    <?php if (!$notifications): ?>
                        <div class="hshr-empty-state hshr-empty-state-compact">
                            <i class="fa-regular fa-circle-check" aria-hidden="true"></i>
                            <p>You are all caught up.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($notifications as $notification): ?>
                            <a class="hshr-notification-item" href="<?= htmlspecialchars($notification['event_url']) ?>">
                                <span class="hshr-notification-icon"><i class="<?= htmlspecialchars($notification['event_icon']) ?>" aria-hidden="true"></i></span>
                                <span>
                                    <strong><?= htmlspecialchars($notification['employee_name']) ?></strong>
                                    <small><?= htmlspecialchars($notification['event_label']) ?> · <?= htmlspecialchars(date('M j, g:i A', strtotime($notification['event_time']))) ?></small>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <button class="hshr-icon-button" type="button" data-open-message-center aria-label="Messages" title="Messages">
            <i class="fa-regular fa-envelope" aria-hidden="true"></i>
            <?php if ($unreadMessageCount > 0): ?>
                <span class="hshr-action-badge"><?= $unreadMessageCount > 99 ? '99+' : $unreadMessageCount ?></span>
            <?php endif; ?>
        </button>

        <div class="hshr-menu" data-hshr-menu="profile">
            <button class="hshr-profile-trigger" type="button" data-hshr-menu-toggle aria-expanded="false">
                <img src="<?= htmlspecialchars($profilePicture) ?>" alt="<?= htmlspecialchars($displayName) ?>" onerror="this.onerror=null;this.src='images/image-not-found.jpg'">
                <span class="hshr-profile-copy">
                    <strong><?= htmlspecialchars($displayName) ?></strong>
                    <small><?= htmlspecialchars($adminRole) ?></small>
                </span>
                <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
            </button>
            <div class="hshr-popover hshr-profile-popover" data-hshr-menu-panel hidden>
                <a href="view_profile.php"><i class="fa-regular fa-user" aria-hidden="true"></i> My profile</a>
                <a href="settings.php"><i class="fa-solid fa-sliders" aria-hidden="true"></i> Settings</a>
                <hr>
                <a href="logout.php" class="hshr-danger-link"><i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i> Sign out</a>
            </div>
        </div>
    </div>
</header>

<div class="hshr-message-overlay" data-message-overlay hidden></div>
<aside class="hshr-message-center" data-message-center aria-hidden="true" data-sender-id="<?= htmlspecialchars((string) $adminId) ?>" data-sender-role="<?= htmlspecialchars($adminRole) ?>">
    <div class="hshr-message-header">
        <div>
            <span class="hshr-eyebrow">Communication</span>
            <h2>Messages</h2>
        </div>
        <button class="hshr-icon-button" type="button" data-close-message-center aria-label="Close messages">
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
    </div>

    <div class="hshr-message-layout">
        <div class="hshr-contact-list" data-contact-list>
            <label class="hshr-search-field">
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                <input type="search" placeholder="Search people" data-contact-search>
            </label>
            <div class="hshr-contacts">
                <?php foreach ($messageContacts as $contact): ?>
                    <?php
                    $contactPicture = hshr_profile_picture_url($contact['profile_picture'] ?? null);
                    $contactName = trim($contact['display_name']) ?: $contact['username'];
                    ?>
                    <button class="hshr-contact" type="button" data-message-contact data-id="<?= htmlspecialchars((string) $contact['contact_id']) ?>" data-role="<?= htmlspecialchars($contact['contact_role']) ?>" data-search="<?= htmlspecialchars(strtolower($contactName . ' ' . $contact['username'])) ?>">
                        <img src="<?= htmlspecialchars($contactPicture) ?>" alt="" onerror="this.onerror=null;this.src='images/image-not-found.jpg'">
                        <span>
                            <strong><?= htmlspecialchars($contactName) ?></strong>
                            <small><?= htmlspecialchars($contact['contact_role']) ?></small>
                        </span>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="hshr-conversation">
            <div class="hshr-conversation-title" data-conversation-title>
                <div>
                    <strong>Select a person</strong>
                    <small>Choose someone to view your conversation.</small>
                </div>
            </div>
            <div class="hshr-chat-body" data-chat-body>
                <div class="hshr-empty-state">
                    <i class="fa-regular fa-comments" aria-hidden="true"></i>
                    <h3>Your conversations</h3>
                    <p>Choose a colleague from the list to start messaging.</p>
                </div>
            </div>
            <form class="hshr-message-composer" data-message-form>
                <input type="text" data-message-input placeholder="Write a message…" autocomplete="off" disabled>
                <button type="submit" disabled aria-label="Send message"><i class="fa-solid fa-paper-plane" aria-hidden="true"></i></button>
            </form>
        </div>
    </div>
</aside>
