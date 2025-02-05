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
    <!-- School Logo at the top -->
    <div class="logo-container text-center">
        <img src="school_logo.png" alt="School Logo" class="logo">
    </div>
    
    <h4 class="text-center">HR Dashboard</h4>
    
    <!-- Sidebar menu items -->
    <?php foreach ($sidebarItems as $item): ?>
        <a href="<?= $item['link'] ?>" class="<?= ($current_page == basename($item['link'])) ? 'active' : '' ?>">
            <i class="<?= $item['icon'] ?>"></i> <?= $item['text'] ?>
        </a>
    <?php endforeach; ?>
    
    <!-- Logout button (placed at the bottom) -->
    <div class="logout">
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<style>
/* Style for the logo */
.logo-container img {
    max-width: 100px; /* Adjust size of the logo */
    height: auto;
    margin-bottom: 15px;
}

/* Style for the sidebar */
.sidebar {
    display: flex;
    flex-direction: column;
    height: 100vh; /* Make the sidebar fill the entire vertical space */
}

/* Style for the logout button */
.logout {
    margin-top: auto; /* This pushes the logout section to the bottom of the sidebar */
}

.logout a {
    color: #fff;
    padding: 10px 20px;
    margin-top: 20px; /* Space between the menu items and the logout button */
    display: flex;
    align-items: center;
    width: 100%;
}

.logout a:hover {
    background-color: #f44336;
}
</style>
