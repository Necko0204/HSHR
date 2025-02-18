<?php
$sidebarItems = [
    ['icon' => 'fas fa-tachometer-alt', 'text' => 'Dashboard', 'links' => ['dashboard.php']],
    ['icon' => 'fas fa-users', 'text' => 'Employees', 'links' => ['employees.php', 'employee_details.php']],
    ['icon' => 'fas fa-wallet', 'text' => 'Payroll', 'links' => ['payroll.php']],
    ['icon' => 'fas fa-calendar-check', 'text' => 'Leave Requests', 'links' => ['leave_requests.php']],
    ['icon' => 'fas fa-file-alt', 'text' => 'Reports', 'links' => ['reports.php']],
    ['icon' => 'fas fa-cog', 'text' => 'Settings', 'links' => ['settings.php','view_profile.php']]
];

$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar" id="sidebar">
<button id="toggleButton" onclick="toggleSidebar()">

        <i class="fas fa-bars"></i>
    </button>
    <div class="logo-container text-center">
        <img src="images/asdasdasd123123123123123.jpg" alt="School Logo" class="logo">
    </div>
    
    <h4 class="text-center">HR Dashboard</h4>
    
    <?php foreach ($sidebarItems as $item): ?>
        <?php 
            $isActive = in_array($current_page, $item['links']) ? 'active' : ''; 
        ?>
        <a href="<?= $item['links'][0] ?>" class="<?= $isActive ?>">
            <i class="<?= $item['icon'] ?>"></i> <span class="text"><?= $item['text'] ?></span>
        </a>
    <?php endforeach; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const contentContainer = document.querySelector('.content-container');
    const navbar = document.querySelector('.navbar');
    const wrapper = document.querySelector('.wrapper'); // Select wrapper element
    const toggleButton = document.getElementById('toggleButton');

    // Check if sidebar was minimized previously from localStorage
    const isMinimized = localStorage.getItem('sidebarMinimized') === 'true';
    if (isMinimized) {
        sidebar.classList.add('minimized');
        contentContainer.classList.add('minimized');
        navbar.classList.add('minimized');
        wrapper.classList.add('minimized'); // Apply minimized class to wrapper
    }

    // Toggle sidebar, wrapper, and content container on button click
    if (toggleButton) {
        toggleButton.addEventListener('click', () => {
            sidebar.classList.toggle('minimized');
            contentContainer.classList.toggle('minimized');
            navbar.classList.toggle('minimized');
            wrapper.classList.toggle('minimized'); // Toggle minimized class on wrapper

            // Store state in localStorage
            localStorage.setItem('sidebarMinimized', sidebar.classList.contains('minimized'));
        });
    }
});

</script>
