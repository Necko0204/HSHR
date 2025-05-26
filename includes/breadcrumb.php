<?php
function generateBreadcrumb() {
    $sidebarItems = [
        'dashboard' => ['Dashboard', 'fas fa-tachometer-alt'],
        'employees' => ['Employees', 'fas fa-users', [
            'employees' => ['Employee List', 'fas fa-list'],
            'staff_accounts' => ['Staff Accounts', 'fas fa-envelope'],
            'employee_details' => ['Employee Details', 'fas fa-id-card'],
            'employee_schedule' => ['Employee Schedule', 'fas fa-calendar-plus'],
            'role_management' => ['Role Management', 'fas fa-user-shield']
        ]],
        'employment_applicants_list' => ['Applicants', 'fas fa-user-plus'],
        'payroll' => ['Payroll', 'fas fa-wallet', [
            'payroll' => ['Payroll', 'fas fa-money-check-alt'],
            'deductions' => ['Deductions', 'fas fa-minus-circle']
        ]],
        'leave_requests' => ['Leave Requests', 'fa-solid fa-person-walking-arrow-right'],
        'employee_attendance' => ['Attendance', 'fas fa-calendar-check'], 
        'reports' => ['Reports', 'fas fa-file-alt'], 
        'settings' => ['Settings', 'fas fa-cog', [
            'settings' => ['General Settings', 'fas fa-tools'],
            'view_profile' => ['View Profile', 'fas fa-user']
        ]]
    ];

    $currentPage = basename($_SERVER['PHP_SELF'], ".php");

    $breadcrumb = '<div class="d-flex justify-content-between align-items-center w-100 mb-2">';
    $breadcrumb .= '<h2 class="breadcrumb-title"><i class="fas fa-users"></i> Human Resource</h2>';
    
    if ($currentPage === 'dashboard') {
        $breadcrumb .= '
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dateRangePicker" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-calendar-alt"></i> This Month
                </button>
                <ul class="dropdown-menu" aria-labelledby="dateRangePicker">
                    <li><a class="dropdown-item" href="#" onclick="setDateRange(15)">Last 15 days</a></li>
                    <li><a class="dropdown-item" href="#" onclick="setDateRange(30)">Last 30 days</a></li>
                    <li><a class="dropdown-item" href="#" onclick="setDateRange(\'month\')">This Month</a></li>
                    <li><a class="dropdown-item" href="#" onclick="setDateRange(\'year\')">This Year</a></li>
                </ul>
            </div>';
    }
    
    if ($currentPage === 'deductions') {
        $breadcrumb .= '<input type="text" id="searchCards" class="form-control ms-auto" placeholder="Search deductions..." style="width: 200px;">';
    }
    
    $breadcrumb .= '</div>';
    $breadcrumb .= '<nav aria-label="breadcrumb">';
    $breadcrumb .= '<ol class="breadcrumb">';
    $breadcrumb .= '<li class="breadcrumb-item"><a href="/HSHR/dashboard.php"><i class="fas fa-home"></i> Home</a></li>';

    foreach ($sidebarItems as $key => $item) {
        if ($currentPage === $key) {
            $breadcrumb .= '<li class="breadcrumb-item active" aria-current="page"><i class="' . $item[1] . '"></i> ' . $item[0] . '</li>';
            break;
        } elseif (isset($item[2]) && array_key_exists($currentPage, $item[2])) {
            $breadcrumb .= '<li class="breadcrumb-item"><a href="/HSHR/' . $key . '.php"><i class="' . $item[1] . '"></i> ' . $item[0] . '</a></li>';
            $breadcrumb .= '<li class="breadcrumb-item active" aria-current="page"><i class="' . $item[2][$currentPage][1] . '"></i> ' . $item[2][$currentPage][0] . '</li>';
            break;
        }
    }

    $breadcrumb .= '</ol>';
    $breadcrumb .= '</nav>';
    $breadcrumb .= '<hr>';

    return $breadcrumb;
}
?>
