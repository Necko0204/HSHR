<?php
$sidebarItems = [
    ['icon' => 'fas fa-users', 'text' => 'Employees', 'link' => '#'],
    ['icon' => 'fas fa-wallet', 'text' => 'Payroll', 'link' => '#'],
    ['icon' => 'fas fa-calendar-check', 'text' => 'Leave Requests', 'link' => '#'],
    ['icon' => 'fas fa-file-alt', 'text' => 'Reports', 'link' => '#'],
    ['icon' => 'fas fa-cog', 'text' => 'Settings', 'link' => '#'],
    ['icon' => 'fas fa-sign-out-alt', 'text' => 'Logout', 'link' => '#']
];
?>
<div class="sidebar">
    <h4 class="text-center">HR Dashboard</h4>
    <?php foreach ($sidebarItems as $item): ?>
        <a href="<?= $item['link'] ?>"><i class="<?= $item['icon'] ?>"></i> <?= $item['text'] ?></a>
    <?php endforeach; ?>
</div>