<?php
require_once __DIR__ . '/includes/admin_session.php';

if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    exit;
}

$currentPage = basename($_SERVER['PHP_SELF'] ?? 'dashboard.php');
$sidebarCollapsed = isset($userData['sidebarOn']) && (int) $userData['sidebarOn'] === 0;

$navigationSections = [
    'Workspace' => [
        [
            'icon' => 'fa-solid fa-table-cells-large',
            'label' => 'Dashboard',
            'href' => 'dashboard.php',
            'pages' => ['dashboard.php'],
        ],
    ],
    'People' => [
        [
            'icon' => 'fa-solid fa-users',
            'label' => 'Employees',
            'pages' => ['employees.php', 'staff_accounts.php', 'employee_details.php', 'employee_schedule.php', 'role_management.php'],
            'children' => [
                ['icon' => 'fa-solid fa-address-book', 'label' => 'Employee directory', 'href' => 'employees.php'],
                ['icon' => 'fa-solid fa-user-shield', 'label' => 'Staff accounts', 'href' => 'staff_accounts.php'],
                ['icon' => 'fa-solid fa-calendar-days', 'label' => 'Work schedules', 'href' => 'employee_schedule.php'],
                ['icon' => 'fa-solid fa-id-badge', 'label' => 'Roles & access', 'href' => 'role_management.php'],
            ],
        ],
        [
            'icon' => 'fa-solid fa-user-plus',
            'label' => 'Applicants',
            'href' => 'employment_applicants_list.php',
            'pages' => ['employment_applicants_list.php'],
        ],
    ],
    'Operations' => [
        [
            'icon' => 'fa-solid fa-wallet',
            'label' => 'Payroll',
            'pages' => ['payroll.php', 'deductions.php'],
            'children' => [
                ['icon' => 'fa-solid fa-money-check-dollar', 'label' => 'Payroll runs', 'href' => 'payroll.php'],
                ['icon' => 'fa-solid fa-receipt', 'label' => 'Deductions', 'href' => 'deductions.php'],
            ],
        ],
        [
            'icon' => 'fa-solid fa-person-walking-arrow-right',
            'label' => 'Leave requests',
            'href' => 'leave_requests.php',
            'pages' => ['leave_requests.php'],
        ],
        [
            'icon' => 'fa-solid fa-calendar-check',
            'label' => 'Attendance',
            'href' => 'employee_attendance.php',
            'pages' => ['employee_attendance.php'],
        ],
    ],
    'Administration' => [
        [
            'icon' => 'fa-solid fa-gear',
            'label' => 'Settings',
            'pages' => ['settings.php', 'view_profile.php'],
            'children' => [
                ['icon' => 'fa-solid fa-sliders', 'label' => 'General settings', 'href' => 'settings.php'],
                ['icon' => 'fa-solid fa-circle-user', 'label' => 'My profile', 'href' => 'view_profile.php'],
            ],
        ],
    ],
];

function hshrNavigationIsActive(array $pages, string $currentPage): bool
{
    return in_array($currentPage, $pages, true);
}
?>

<link rel="stylesheet" href="assets/css/hshr-next.css?v=<?= rawurlencode((string) filemtime(__DIR__ . '/assets/css/hshr-next.css')) ?>">
<script>
document.body.classList.add('hshr-app', 'hshr-admin');
<?php if (!empty($userData['darkmodeOn'])): ?>document.body.classList.add('hshr-dark');<?php endif; ?>
</script>

<aside class="sidebar hshr-sidebar<?= $sidebarCollapsed ? ' minimized' : '' ?>" id="sidebar" data-hshr-sidebar aria-label="Primary navigation">
    <div class="hshr-sidebar-brand">
        <img src="images/asdasdasd123123123123123.jpg" alt="Holy Spirit School of Imus" class="hshr-brand-mark">
        <div class="hshr-brand-copy">
            <strong>Holy Spirit</strong>
            <span>Human Resources</span>
        </div>
        <button class="hshr-icon-button hshr-sidebar-toggle" id="toggleButton" data-hshr-sidebar-toggle type="button" aria-controls="sidebar" aria-expanded="<?= $sidebarCollapsed ? 'false' : 'true' ?>" aria-label="<?= $sidebarCollapsed ? 'Expand navigation' : 'Collapse navigation' ?>" title="<?= $sidebarCollapsed ? 'Expand navigation' : 'Collapse navigation' ?>">
            <i class="fa-solid fa-angles-left" aria-hidden="true"></i>
        </button>
    </div>

    <nav class="hshr-navigation">
        <?php foreach ($navigationSections as $sectionLabel => $items): ?>
            <section class="hshr-nav-section" aria-labelledby="nav-<?= htmlspecialchars(strtolower(str_replace(' ', '-', $sectionLabel))) ?>">
                <p class="hshr-nav-label" id="nav-<?= htmlspecialchars(strtolower(str_replace(' ', '-', $sectionLabel))) ?>">
                    <?= htmlspecialchars($sectionLabel) ?>
                </p>

                <?php foreach ($items as $item): ?>
                    <?php $isActive = hshrNavigationIsActive($item['pages'], $currentPage); ?>
                    <?php if (!empty($item['children'])): ?>
                        <details class="hshr-nav-group<?= $isActive ? ' is-active' : '' ?>"<?= $isActive ? ' open' : '' ?>>
                            <summary class="hshr-nav-item" title="<?= htmlspecialchars($item['label']) ?>">
                                <i class="<?= htmlspecialchars($item['icon']) ?> hshr-nav-icon" aria-hidden="true"></i>
                                <span class="hshr-nav-text"><?= htmlspecialchars($item['label']) ?></span>
                                <i class="fa-solid fa-chevron-down hshr-nav-chevron" aria-hidden="true"></i>
                            </summary>
                            <div class="hshr-subnav">
                                <?php foreach ($item['children'] as $child): ?>
                                    <?php $childActive = $currentPage === $child['href']; ?>
                                    <a class="hshr-subnav-item<?= $childActive ? ' is-active' : '' ?>" href="<?= htmlspecialchars($child['href']) ?>">
                                        <i class="<?= htmlspecialchars($child['icon']) ?>" aria-hidden="true"></i>
                                        <span><?= htmlspecialchars($child['label']) ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </details>
                    <?php else: ?>
                        <a class="hshr-nav-item<?= $isActive ? ' is-active' : '' ?>" href="<?= htmlspecialchars($item['href']) ?>" title="<?= htmlspecialchars($item['label']) ?>"<?= $isActive ? ' aria-current="page"' : '' ?>>
                            <i class="<?= htmlspecialchars($item['icon']) ?> hshr-nav-icon" aria-hidden="true"></i>
                            <span class="hshr-nav-text"><?= htmlspecialchars($item['label']) ?></span>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </section>
        <?php endforeach; ?>
    </nav>

    <div class="hshr-sidebar-footer">
        <div class="hshr-system-status" title="Local HR system is online">
            <span class="hshr-status-dot" aria-hidden="true"></span>
            <span class="hshr-nav-text">System operational</span>
        </div>
        <p class="hshr-nav-text">HSSII HR · <?= date('Y') ?></p>
    </div>
</aside>

<button class="sidebar-backdrop hshr-sidebar-backdrop" id="sidebarBackdrop" type="button" aria-label="Close navigation"></button>
<script>document.documentElement.dataset.hshrCsrfToken = <?= json_encode(hshr_csrf_token(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;</script>
<script defer src="assets/js/hshr-next.js?v=<?= rawurlencode((string) filemtime(__DIR__ . '/assets/js/hshr-next.js')) ?>"></script>
