<?php
$sidebarItems = [
    ['icon' => 'fas fa-tachometer-alt', 'text' => 'Dashboard', 'link' => 'dashboard.php'],
    ['icon' => 'fas fa-users', 'text' => 'Employees', 'link' => 'employees.php'],
    ['icon' => 'fas fa-wallet', 'text' => 'Payroll', 'link' => 'payroll.php'],
    ['icon' => 'fas fa-calendar-check', 'text' => 'Leave Requests', 'link' => 'leave_requests.php'],
    ['icon' => 'fas fa-file-alt', 'text' => 'Reports', 'link' => 'reports.php'],
    ['icon' => 'fas fa-cog', 'text' => 'Settings', 'link' => 'settings.php']
];

$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <h4 class="text-center">HR Dashboard</h4>
    <?php foreach ($sidebarItems as $item): ?>
        <a href="<?= $item['link'] ?>" class="<?= ($current_page == basename($item['link'])) ? 'active' : '' ?>">
            <i class="<?= $item['icon'] ?>"></i> <?= $item['text'] ?>
        </a>
    <?php endforeach; ?>
    <div class="logout">
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<style>
    .sidebar a.active {
        background-color: #007bff;
        font-weight: bold;
    }
</style>