<?php
$sidebarItems = [
    ['icon' => 'fas fa-tachometer-alt', 'text' => 'Dashboard', 'links' => ['dashboard.php']],
    ['icon' => 'fas fa-users', 'text' => 'Employees', 'links' => ['employees.php', 'employee_details.php']], // Multiple Pages
    ['icon' => 'fas fa-wallet', 'text' => 'Payroll', 'links' => ['payroll.php']],
    ['icon' => 'fas fa-calendar-check', 'text' => 'Leave Requests', 'links' => ['leave_requests.php']],
    ['icon' => 'fas fa-file-alt', 'text' => 'Reports', 'links' => ['reports.php']],
    ['icon' => 'fas fa-cog', 'text' => 'Settings', 'links' => ['settings.php','view_profile.php']]
];

$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <!-- School Logo at the top -->
    <div class="logo-container text-center">
        <img src="images/asdasdasd123123123123123.jpg" alt="School Logo" class="logo">
    </div>
    
    <h4 class="text-center">HR Dashboard</h4>
    
    <!-- Sidebar menu items -->
    <?php foreach ($sidebarItems as $item): ?>
        <?php 
            // Check if the current page matches any link in the array
            $isActive = in_array($current_page, $item['links']) ? 'active' : ''; 
        ?>
        <a href="<?= $item['links'][0] ?>" class="<?= $isActive ?>">
            <i class="<?= $item['icon'] ?>"></i> <?= $item['text'] ?>
        </a>
    <?php endforeach; ?>
</div>

<style>
/* Logo Styling */
.logo-container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px;
}

.logo {
    width: 80px; /* Adjust as needed */
    height: auto; /* Maintain aspect ratio */
    max-width: 100px; /* Prevents it from being too large */
    max-height: 100px;
    border-radius: 50%;
}


</style>