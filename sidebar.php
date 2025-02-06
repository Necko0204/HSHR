<?php
$sidebarItems = [
    ['icon' => 'fas fa-tachometer-alt', 'text' => 'Dashboard', 'links' => ['dashboard.php']],
    ['icon' => 'fas fa-users', 'text' => 'Employees', 'links' => ['employees.php', 'employee_details.php']], // Multiple Pages
    ['icon' => 'fas fa-wallet', 'text' => 'Payroll', 'links' => ['payroll.php']],
    ['icon' => 'fas fa-calendar-check', 'text' => 'Leave Requests', 'links' => ['leave_requests.php']],
    ['icon' => 'fas fa-file-alt', 'text' => 'Reports', 'links' => ['reports.php']],
    ['icon' => 'fas fa-cog', 'text' => 'Settings', 'links' => ['settings.php']]
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
        <?php 
            // Check if the current page matches any link in the array
            $isActive = in_array($current_page, $item['links']) ? 'active' : ''; 
        ?>
        <a href="<?= $item['links'][0] ?>" class="<?= $isActive ?>">
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
    max-width: 100px;
    height: auto;
    margin-bottom: 15px;
}

/* Style for the sidebar */
.sidebar {
    display: flex;
    flex-direction: column;
    height: 100vh;
}

/* Style for the logout button */
.logout {
    margin-top: auto;
}

.logout a {
    color: #fff;
    padding: 10px 20px;
    margin-top: 20px;
    display: flex;
    align-items: center;
    width: 100%;
}

.logout a:hover {
    background-color:rgb(255, 255, 255);
}

.active {
    background-color: #007bff;
    color: white;
}
</style>
