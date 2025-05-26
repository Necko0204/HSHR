<?php
$sidebarItems = [
    ['icon' => 'fas fa-tachometer-alt', 'text' => 'Dashboard', 'links' => ['dashboard.php']],
    [
        'icon' => 'fas fa-users', 'text' => 'Employees', 'links' => [
            'employees.php',
            'staff_accounts.php',
            'employee_details.php',
            'employee_schedule.php',
            'role_management.php',
            // 'department_management.php'
        ],
        'submenu_icons' => [
            'employees.php' => 'fas fa-list', 
            'staff_accounts.php' => 'fas fa-envelope', 
            'employee_details.php' => 'fas fa-id-card', 
            'employee_schedule.php' => 'fas fa-calendar-plus', 
            'role_management.php' => 'fas fa-user-tag', 
            // 'department_management.php' => 'fas fa-building'
        ]
    ],
    ['icon' => 'fas fa-user-plus', 'text' => 'Applicants', 'links' => ['employment_applicants_list.php']],
    [
        'icon' => 'fas fa-wallet', 'text' => 'Payroll', 'links' => [
            'payroll.php',
            'deductions.php'
        ],
        'submenu_icons' => [
            'payroll.php' => 'fas fa-money-check-alt',
            'deductions.php' => 'fas fa-minus-circle'
        ]
    ],
    ['icon' => 'fa-solid fa-person-walking-arrow-right', 'text' => 'Leave Requests', 'links' => ['leave_requests.php']],
    ['icon' => 'fas fa-calendar-check', 'text' => 'Attendance', 'links' => ['employee_attendance.php']],
    // ['icon' => 'fas fa-file-alt', 'text' => 'Reports', 'links' => ['reports.php']],
    [
        'icon' => 'fas fa-cog', 'text' => 'Settings', 'links' => [
            'settings.php',
            'view_profile.php',
        ],
        'submenu_icons' => [
            'settings.php' => 'fas fa-tools',
            'view_profile.php' => 'fas fa-user',
        ]
    ]
];

$current_page = basename($_SERVER['PHP_SELF']);

$currentDay = date('d');
$currentMonth = date('F');
$currentYear = date('Y');
$currentWeekday = date('l');
?>

<div class="sidebar" id="sidebar">
    <button id="toggleButton" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Add some space between the toggle button and the calendar -->
    <div style="margin-top: 30px;"></div>
    <!-- Calendar and Time Display (replacing logo and school name) -->
    <div class="calendar-time-container">
    <!-- Calendar Display -->
    <div class="calendar-card">
        <div class="calendar-content">
            <i class="fas fa-calendar-alt"></i> <!-- Calendar icon -->
            <div>
                <div class="calendar-month"><?php echo $currentMonth; ?></div>
                <div class="calendar-day"><?php echo $currentDay; ?></div>
                <div class="calendar-weekday"><?php echo $currentWeekday; ?></div>
                <div class="calendar-year"><?php echo $currentYear; ?></div>
            </div>
        </div>
    </div>

    <!-- Digital Clock -->
    <div class="digital-clock">
        <div class="clock-content">
            <i class="fas fa-clock"></i> <!-- Clock icon -->
            <div id="clock"></div>
        </div>
    </div>



   
        <!-- Minimized Calendar and Clock (shown only when sidebar is minimized) -->
        <div class="mini-calendar-time">
            <div class="mini-calendar">
                <div class="mini-day"><?php echo $currentDay; ?></div>
                <div class="mini-month"><?php echo substr($currentMonth, 0, 3); ?></div>
            </div>
            <div class="mini-clock" id="mini-clock"></div>
        </div>
    </div>

    <hr class="sidebar-divider"> 
    
    <div class="sidebar-content">
        <?php foreach ($sidebarItems as $index => $item): ?>
            <?php 
                $isActive = in_array($current_page, $item['links']) ? 'active' : ''; 
                $hasSubmenu = count($item['links']) > 1; 
                $menuId = "menu-{$index}";
                $expanded = $isActive ? 'show' : ''; // Keep open if active
            ?>
            <div class="sidebar-item">
                <?php if ($hasSubmenu): ?>
                    <a href="#" class="menu-toggle <?= $isActive ?>" data-bs-toggle="collapse" data-bs-target="#<?= $menuId ?>" aria-expanded="<?= $expanded ? 'true' : 'false' ?>">
                        <i class="fas fa-chevron-right arrow-icon"></i> 
                        <i class="<?= $item['icon'] ?> me-2"></i> 
                        <span class="text"><?= $item['text'] ?></span>
                    </a>

                    <div id="<?= $menuId ?>" class="collapse <?= $expanded ?>">
                        <ul class="submenu">
                            <?php foreach ($item['links'] as $link): ?>
                                <?php 
                                    if ($link === 'employee_details.php') continue; // Hide Employee Details
                                    $formattedText = ($link === 'employees.php') ? "Employee List" : ucfirst(str_replace('_', ' ', basename($link, ".php"))); // Rename Employees to Employee List
                                    $submenuIcon = $item['submenu_icons'][$link] ?? 'fas fa-circle'; // Default icon
                                ?>
                                        <li>
                                    <a href="<?= $link ?>" class="<?= ($current_page === $link) ? 'active' : '' ?>">
                                        <i class="<?= $submenuIcon ?> me-2"></i>
                                        <span class="text"><?= $formattedText ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= $item['links'][0] ?>" class="<?= $isActive ?>">
                        <i class="<?= $item['icon'] ?> me-2"></i> 
                        <span class="text"><?= $item['text'] ?></span>
                    </a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <hr class="sidebar-divider2"> 

    <div class="sidebar-footer">
        <span class="credits">© 2025 NCST BSIT Batch 2025</span>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Submenu active state handling
    document.querySelectorAll(".submenu a.active").forEach((activeLink) => {
        let parentItem = activeLink.closest(".sidebar-item");
        if (parentItem) {
            parentItem.classList.add("has-active-submenu");
        }
    });
    
    // Digital clock functionality
    function updateClock() {
        const now = new Date();
        let hours = now.getHours();
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        
        hours = hours % 12;
        hours = hours ? hours : 12; // Convert 0 to 12
        hours = hours.toString().padStart(2, '0');
        
        // Update main clock
        document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds} ${ampm}`;
        
        // Update mini clock (simplified format)
        const miniClock = document.getElementById('mini-clock');
        if (miniClock) {
            miniClock.textContent = `${hours}:${minutes}`;
        }
        
        // Pulse animation on seconds change
        if (seconds === '00') {
            document.getElementById('clock').classList.add('pulse');
            if (miniClock) miniClock.classList.add('pulse');
            
            setTimeout(() => {
                document.getElementById('clock').classList.remove('pulse');
                if (miniClock) miniClock.classList.remove('pulse');
            }, 1000);
        }
    }
    
    // Update clock immediately and then every second
    updateClock();
    setInterval(updateClock, 1000);
});


</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".submenu a.active").forEach((activeLink) => {
        let parentItem = activeLink.closest(".sidebar-item");
        if (parentItem) {
            parentItem.classList.add("has-active-submenu");
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const contentContainer = document.querySelector('.content-container');
    const navbar = document.querySelector('.navbar');
    const wrapper = document.querySelector('.wrapper');
    const toggleButton = document.getElementById('toggleButton');

    let hasAnimated = localStorage.getItem('sidebarAnimated') === 'true';
    let sidebarState = 1; // Default: maximized

    // Hide elements before setting the correct state
    sidebar.style.visibility = "hidden";
    contentContainer.style.visibility = "hidden";
    navbar.style.visibility = "hidden";
    wrapper.style.visibility = "hidden";

    fetch('includes/get_sidebar_state.php')
        .then(response => response.json())
        .then(data => {
            console.log('Sidebar state:', data);
            sidebarState = data.sidebarOn; // Store sidebar state

            if (sidebarState === 1) {
                sidebar.classList.remove('minimized');
                contentContainer.classList.remove('minimized');
                navbar.classList.remove('minimized');
                wrapper.classList.remove('minimized');
            } else {
                sidebar.classList.add('minimized');
                contentContainer.classList.add('minimized');
                navbar.classList.add('minimized');
                wrapper.classList.add('minimized');

                // Perform one-time animation if it hasn't happened before
                if (!hasAnimated) {
                    setTimeout(() => {
                        sidebar.classList.remove('minimized');
                        contentContainer.classList.remove('minimized');
                        navbar.classList.remove('minimized');
                        wrapper.classList.remove('minimized');

                        setTimeout(() => {
                            sidebar.classList.add('minimized');
                            contentContainer.classList.add('minimized');
                            navbar.classList.add('minimized');
                            wrapper.classList.add('minimized');

                            localStorage.setItem('sidebarAnimated', 'true'); // Store that animation happened
                        }, 300);
                    }, 100);
                }
            }

            // Show elements and apply animations for future toggles
            setTimeout(() => {
                sidebar.style.visibility = "visible";
                contentContainer.style.visibility = "visible";
                navbar.style.visibility = "visible";
                wrapper.style.visibility = "visible";

                // **Apply animations only after the initial state is set**
                setTimeout(() => {
                    sidebar.classList.add('animated');
                    contentContainer.classList.add('animated');
                    navbar.classList.add('animated');
                    wrapper.classList.add('animated');
                }, 10);
            }, 10);
        })
        .catch(error => console.error('Fetch error:', error));

    if (toggleButton) {
        toggleButton.addEventListener('click', () => {
            sidebar.classList.toggle('minimized');
            contentContainer.classList.toggle('minimized');
            navbar.classList.toggle('minimized');
            wrapper.classList.toggle('minimized');

            // Flip sidebar state
            sidebarState = sidebar.classList.contains('minimized') ? 0 : 1;

            // Send updated state to the server
            fetch('logics/update_sidebar_state.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ sidebarOn: sidebarState })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Sidebar update:', data);
            })
            .catch(error => console.error('Update error:', error));
        });
    }
});


</script>