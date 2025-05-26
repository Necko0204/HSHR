<?php
session_name('admin_session');
session_start();

include 'includes/breadcrumb.php';
include 'db_config.php';
include 'helper.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$sender_id = $_SESSION['admin_id'];
$sender_role = $_SESSION['position'];

// Fetch current total staff
$query = "SELECT COUNT(id) AS total_staff FROM employees";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$total_staff = $row['total_staff'];

// Fetch current active teachers
$query = "SELECT COUNT(employee_id) AS active_teachers FROM staff_accounts WHERE status = 'active'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$active_teachers = $row['active_teachers'];

// Get today's and yesterday's dates
$today = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));

// Fetch yesterday's total staff and active teachers
$query = "SELECT total_staff, active_teachers FROM historical_data WHERE date = '$yesterday'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$yesterday_total_staff = $row ? $row['total_staff'] : 0;
$yesterday_active_teachers = $row ? $row['active_teachers'] : 0;

// Calculate trends
$total_staff_trend = $total_staff - $yesterday_total_staff;
$active_teachers_trend = $active_teachers - $yesterday_active_teachers;

// Calculate percentage change
$total_staff_percentage = $yesterday_total_staff > 0 ? round(($total_staff_trend / $yesterday_total_staff) * 100, 2) : 0;
$active_teachers_percentage = $yesterday_active_teachers > 0 ? round(($active_teachers_trend / $yesterday_active_teachers) * 100, 2) : 0;

// Insert today's data if not already inserted
$query = "SELECT COUNT(*) AS count FROM historical_data WHERE date = '$today'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row['count'] == 0) {
    $query = "INSERT INTO historical_data (date, total_staff, active_teachers) 
              VALUES ('$today', $total_staff, $active_teachers)";
    mysqli_query($conn, $query);
}

// Function to generate trend text
function getTrendMessage($trend, $percentage) {
    if ($trend > 0) {
        return "<span class='text-success'><i class='fas fa-arrow-up'></i> Increasing by $percentage%</span>";
    } elseif ($trend < 0) {
        return "<span class='text-danger'><i class='fas fa-arrow-down'></i> Decreasing by $percentage%</span>";
    } else {
        return "<span class='text-warning'><i class='fas fa-equals'></i> No change</span>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" href="images/asdasdasd123123123123123.jpg">
        <title>Holy Spirit Human Resource</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link rel="stylesheet" href="background.css">
        <style>
            canvas {
                max-width: 100%;
                max-height: 500px;
                display: block; /* Ensures no extra space below */
            }

            body, html {
                overflow-x: hidden; /* Hides horizontal scrollbar */
            }    
            .chart-container {
            width: 100% !important;
            height: 400px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0;
            margin: 0;
            }

            p {
                font-weight: normal !important;
            }
    </style>
    </head>
<body>
        <!-- Sidebar & Navbar in a separate container -->
        <div class="main-container">
            <?php include 'sidebar.php'; ?>
        </div>
        <div class="content-container">
            <?php include 'nav_header.php'; ?>
            </div>
            <main class="wrapper">
                <?php
                echo generateBreadcrumb();
                ?>
                <!-- Card with Falling Stars, Photo, Pulsing Icon, and Time -->
                <div class="card glass-card shadow-lg mb-4 d-flex flex-column align-items-center text-center"
                    style="margin: 20px auto; max-width: 5000px; padding: 30px; border-radius: 15px; position: relative; overflow: hidden;">

                    <div class="stars">
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                    </div>

                    <div class="sun-container">
                        <div class="sun theme-icon" id="sun-icon">☀️</div>
                    </div>

                    <div class="moon-container">
                        <div class="moon theme-icon" id="moon-icon">🌙</div>
                    </div>


                    <div class="cloud-container">
                        <div class="cloud">☁️</div>
                        <div class="cloud">☁️</div>
                        <div class="cloud">☁️</div>
                    </div>

                    
                    <div class="flowers">
                        <div class="flower"></div>
                        <div class="flower"></div>
                        <div class="flower"></div>
                        <div class="flower"></div>
                        <div class="flower"></div>
                        <div class="flower"></div>
                        <div class="flower"></div>
                        <div class="flower"></div>
                        <div class="flower"></div>
                    </div> 

                    <div class="profile-container">
                        <img src="<?php echo !empty($userData['profile_picture']) ? $userData['profile_picture'] : 'uploads/profile_pictures/default.jpg'; ?>"
                            alt="Profile Picture"
                            class="profile-image">
                        <div class="status-indicator">
                            <div class="pulsing-icon"></div>
                            <div class="pulsing-ring"></div>
                        </div>
                    </div>
    
                    <div class="card-body mt-3">
                        <h5 class="card-title">
                            Hello! <span id="greeting"></span> <?php echo isset($userData['name']) ? $userData['name'] : 'Guest'; ?>.
                        </h5>
                        <h6><?php echo isset($userData['position']) ? $userData['position'] : 'Guest'; ?></h6>
                        <p class="time-container" id="ph-time"></p>
                    <!-- <div class="stickman" id="stickman">
                        <svg width="50" height="80" xmlns="http://www.w3.org/2000/svg">
                        
                        <circle cx="25" cy="10" r="5" stroke="black" stroke-width="2" fill="none" />
                        
                        <line x1="25" y1="15" x2="25" y2="40" stroke="black" stroke-width="2" />
                        
                        <line id="arm-left" x1="25" y1="25" x2="15" y2="35" stroke="black" stroke-width="2" />
                        <line id="arm-right" x1="25" y1="25" x2="35" y2="35" stroke="black" stroke-width="2" />
                        
                        <line id="leg-left" x1="25" y1="40" x2="15" y2="60" stroke="black" stroke-width="2" />
                        <line id="leg-right" x1="25" y1="40" x2="35" y2="60" stroke="black" stroke-width="2" />
                        </svg>
                    </div> -->
                    </div>
                    </div>
                </div>

            </section>
<div class="container-fluid">
        <!-- Divider and Note for Summary Cards -->
        <div class="row">
            <div class="col-12 mb-2">
                <hr>
                <p class="text-muted text-center fw-semibold">📌 Summary of Staff and Pending Items</p>
            </div>
        </div>

       <!-- Summary Cards -->
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card glass-card shadow-sm border-left-primary">
            <div class="card-body text-center">
                <h5 class="card-title text-primary">Total Staff</h5>
                <h3 class="font-weight-bold">
                    <span id="total-staff"><?php echo $total_staff; ?></span>
                    <span id="total-staff-change" style="font-size: 14px; color: gray;"> → 0%</span>
                </h3>
                <div id="chart-total-staff" style="height: 50px;"></div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card glass-card shadow-sm border-left-success">
            <div class="card-body text-center">
                <h5 class="card-title text-success">Active Teachers</h5>
                <h3 class="font-weight-bold">
                    <span id="active-teachers"><?php echo $active_teachers; ?></span>
                    <span id="active-teachers-change" style="font-size: 14px; color: gray;"> → 0%</span>
                </h3>
                <div id="chart-active-teachers" style="height: 50px;"></div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card glass-card">
            <div class="card-body text-center">
                <h5 class="card-title">Pending Evaluations</h5>
                <h3>
                    <span id="pending-evaluations">15</span>
                    <span id="pending-evaluations-change" style="font-size: 14px; color: gray;"> → 0%</span>
                </h3>
                <div id="chart-pending-evaluations" style="height: 50px;"></div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card glass-card">
            <div class="card-body text-center">
                <h5 class="card-title">Pending Payroll</h5>
                <h3>
                    <span id="pending-payroll">5</span>
                    <span id="pending-payroll-change" style="font-size: 14px; color: gray;"> → 0%</span>
                </h3>
                <div id="chart-pending-payroll" style="height: 50px;"></div>
            </div>
        </div>
    </div>
</div>


<!-- Divider and Note for Chart Section -->
<div class="row">
    <div class="col-md-6">
        <div class="card glass-card">
            <div class="card-body">
                <h5 class="card-title fw-bold"> <i class="fa fa-line-chart"></i> Performance Chart</h5>
                <div id="performanceChart"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card glass-card">
            <div class="card-body">
                <h5 class="card-title fw-bold"><i class='fas fa-building'></i> Department Chart</h5>
                <div id="departmentChart"></div>
            </div>
        </div>
    </div>
</div>

   <!-- 📊 Modernized Chart Layout with ApexCharts -->
<div class="row">
    <div class="col-12 mb-3">
        <hr>
        <p class="text-muted text-center fw-semibold">📌 Attendance & Evaluation Insights</p>
    </div>
</div>

<div class="row">
    <!-- 🏆 Attendance Overview -->
    <div class="col-md-6 mb-4">
        <div class="card glass-card p-4">
            <h5 class="text-center text-black fw-bold"><i class='fas fa-book'></i> Attendance Overview</h5>
            <hr>
            <div class="row align-items-center">
                <div class="col-7">
                    <div id="attendanceChart"></div>
                </div>
                <div class="col-5 text-black">
                    <p class="mb-0">✔ High attendance rate, reflecting strong employee participation.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 🌟 Evaluation Summary -->
    <div class="col-md-6 mb-4">
        <div class="card glass-card p-4">
            <h5 class="text-center text-black fw-bold"><i class='fas fa-balance-scale'></i> Evaluation Summary</h5>
            <hr>
            <div class="row align-items-center">
                <div class="col-5 text-black">
                    <p class="mb-0">📊 Majority of employees are rated 'Good' or 'Excellent'.</p>
                </div>
                <div class="col-7">
                    <div id="evaluationChart"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 🔍 Financial & Report Trends -->
<div class="row">
    <div class="col-12 mb-3">
        <hr>
        <p class="text-muted text-center fw-semibold">📌 Financial & Performance Trends</p>
    </div>
</div>

<div class="row">
    <!-- 💰 Payslip Trends -->
    <div class="col-md-6 mb-4">
        <div class="card glass-card p-4">
            <h5 class="text-center text-black fw-bold"><i class='fas fa-money-check'></i> Payslip Trends</h5>
            <hr>
            <div class="row align-items-center">
                <div class="col-7">
                    <div id="payslipChart"></div>
                </div>
                <div class="col-5 text-black">
                    <p class="mb-0">📈 A steady increase in generated payslips indicates stable payroll processing.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 📊 Report Trends -->
    <div class="col-md-6 mb-4">
        <div class="card glass-card p-4">
            <h5 class="text-center text-black fw-bold"><i class='fas fa-bullhorn'></i> Report Trends</h5>
            <hr>
            <div class="row align-items-center">
                <div class="col-5 text-black">
                    <p class="mb-0">📉 This chart tracks attendance, evaluations, leave requests, and payroll trends.</p>
                </div>
                <div class="col-7">
                    <div id="reportTrendChart"></div>
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- Divider and Note for Leave Requests Table -->
    <div class="row">
        <div class="col-12 mb-2">
            <hr>
            <p class="text-muted text-center fw-semibold">📌 Leave Requests Overview</p>
        </div>
    </div>
    <div class="col-12 mb-4">
        <div class="card glass-card p-4">
        <h5 class="text-center fw-bold"><i class="fas fa-calendar-alt"></i> Leave Requests</h5>
            <div class="table-responsive" style="max-height: 300px; overflow-y: auto; overflow-x: auto; white-space: nowrap;">
                <?php
                $sql = "SELECT l.leave_id, l.employee_id, e.firstname, e.lastname, lt.leave_name, l.leave_start_date, l.leave_end_date, l.total_days, l.status, l.request_date 
                        FROM leave_requests l
                        JOIN employees e ON l.employee_id = e.id
                        JOIN leave_types lt ON l.leave_type_id = lt.leave_type_id
                        WHERE l.status = 'Pending'";
                $result = $conn->query($sql);
                ?>
                <table class="table table-borderless table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="hidden-id" style="min-width: 100px;">Leave ID</th>
                            <th style="min-width: 150px;">Employee Name</th>
                            <th style="min-width: 150px;">Leave Type</th>
                            <th style="min-width: 150px;">Start Date</th>
                            <th style="min-width: 150px;">End Date</th>
                            <th style="min-width: 150px;">Total Days</th>
                            <th style="min-width: 150px;">Request Date</th>
                            <th style="min-width: 150px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="hidden-id"><?= htmlspecialchars($row["leave_id"]) ?></td>
                                <td><?= htmlspecialchars($row["firstname"] . ' ' . $row["lastname"]) ?></td>
                                <td><?= htmlspecialchars($row["leave_name"]) ?></td>
                                <td><?= htmlspecialchars($row["leave_start_date"]) ?></td>
                                <td><?= htmlspecialchars($row["leave_end_date"]) ?></td>
                                <td><?= htmlspecialchars($row["total_days"]) ?></td>
                                <td><?= htmlspecialchars($row["request_date"]) ?></td>
                                <td class="px-3 py-2">
                                <span class="badge bg-warning text-white"><?= htmlspecialchars($row["status"]) ?></span>
                                        
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php $conn->close(); ?>
        </div>
    </div>

    <!-- Divider and Note for Summary Data Section -->
    <div class="row">
        <div class="col-12 mb-2">
            <hr>
            <p class="text-muted text-center fw-semibold">📌 Summary Data</p>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-md-6" data-aos="fade-left">
            <div class="card glass-card p-4">
            <h5 class="text-center fw-bold"><i class='far fa-calendar-check'></i> Total Attendance</h5>
            <p id="totalAttendance" class="fs-4 text-center text-muted">Fetching data...</p>
            </div>
        </div>
        <div class="col-md-6" data-aos="fade-right" data-aos-delay="100">
            <div class="card glass-card p-4">
            <h5 class="text-center fw-bold"><i class='far fa-check-circle'></i> Average Evaluation Score</h5>
                <p id="averageEvaluation" class="fs-4 text-center text-muted">Fetching data...</p>
            </div>
        </div>
    </div>
</div>
            </main>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="background.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "timeOut": "1000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};
    function generateStars() {
        const starContainer = document.querySelector('.stars');
        for (let i = 0; i < 20; i++) {
            let star = document.createElement('div');
            star.className = 'star';
            star.style.left = Math.random() * 100 + 'vw';
            star.style.animationDuration = (Math.random() * 2 + 2) + 's';
            star.style.top = Math.random() * 100 + 'px';
            starContainer.appendChild(star);
        }
    }
    generateStars();

    function updateTime() {
        const options = { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
        const now = new Date().toLocaleTimeString('en-US', options);
       

        const hour = new Date().getHours();
        let greeting = "";
        if (hour >= 7 && hour < 12) {
            greeting = "Good Morning,";
        } else if (hour >= 12 && hour < 17) {
            greeting = "Good Afternoon,";
        } else {
            greeting = "Good Evening,";
        }
        document.getElementById('greeting').textContent = greeting;
    }
    setInterval(updateTime, 1000);
    updateTime();
    </script>


<script>
    var totalStaffData = <?php echo json_encode([$yesterday_total_staff, $total_staff]); ?>;
    var activeTeachersData = <?php echo json_encode([$yesterday_active_teachers, $active_teachers]); ?>;
</script>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
        // Check if the animation has already run in this session
        if (!sessionStorage.getItem("aosPlayed")) {
            AOS.init();
            sessionStorage.setItem("aosPlayed", "true"); // Mark animation as played
        } else {
            // If animation has already played, disable AOS effects
            document.querySelectorAll("[data-aos]").forEach(el => {
                el.removeAttribute("data-aos");
            });
        }
    });

    function createGradient(ctx, color1, color2) {
            let gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, color1);
            gradient.addColorStop(1, color2);
            return gradient;
        }

 // === Performance Chart (Line Chart with Gradient) ===
        var performanceOptions = {
            series: [{
                name: 'Performance A',
                data: [75, 80, 85, 90, 95, 92, 96, 88, 85, 90, 93, 97]
            }, {
                name: 'Performance B',
                data: [65, 78, 82, 85, 90, 87, 91, 83, 80, 85, 88, 92]
            }],
            chart: {
                type: 'line',
                height: 350,
                background: 'transparent', // Transparent background
                dropShadow: {
                    enabled: true,
                    top: 10,
                    left: 0,
                    blur: 10,
                    color: '#000',
                    opacity: 0.4
                },
                toolbar: {
                    show: false
                }
            },
            colors: ['#00f0ff', '#ff4d4d'], // Vibrant, neon-like colors
            stroke: {
                width: 4,
                curve: 'smooth',
                lineCap: 'round'
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'horizontal',
                    shadeIntensity: 0.8,
                    gradientToColors: ['#00f0ff', '#ff7373'],
                    inverseColors: false,
                    opacityFrom: 0.9,
                    opacityTo: 0.1,
                    stops: [0, 100]
                }
            },
            markers: {
                size: 7,
                strokeColors: '#A9A9A9',
                strokeWidth: 3,
                colors: ['#00f0ff', '#ff4d4d'],
                hover: {
                    size: 10,
                    strokeWidth: 4,
                    strokeColors: '#000'
                }
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                labels: {
                    style: {
                        colors: '#A9A9A9', // Light gray for dark mode
                        fontSize: '14px',
                        fontWeight: 500
                    }
                },
                axisBorder: {
                    color: '#000'
                },
                axisTicks: {
                    color: '#000'
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#A9A9A9',
                        fontSize: '14px',
                        fontWeight: 500
                    }
                },
                axisBorder: {
                    color: '#555'
                },
                axisTicks: {
                    color: '#555'
                }
            },
            tooltip: {
                theme: 'dark',
                style: {
                    fontSize: '14px'
                },
                y: {
                    formatter: (value) => `${value}%`
                },
                x: {
                    show: true
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                labels: {
                    colors: '#A9A9A9'
                },
                markers: {
                    width: 14,
                    height: 14,
                    radius: 14,
                    offsetX: -3
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 0
                }
            }
        };

        var performanceChart = new ApexCharts(document.querySelector("#performanceChart"), performanceOptions);
        performanceChart.render();


    // === Department Chart (Stacked Bar Chart) ===
 var departmentOptions = {
    series: [{
        name: 'Stream 1',
        data: [5, 10, 15, 8, 12, 18, 20, 22, 25, 28, 30, 35]
    }, {
        name: 'Stream 2',
        data: [3, 7, 12, 10, 15, 20, 25, 27, 30, 33, 35, 40]
    }],
    chart: {
        type: 'bar',
        height: 350,
        stacked: true,
        background: 'transparent', // Transparent background
        dropShadow: {
            enabled: true,
            top: 5,
            left: 5,
            blur: 8,
            color: '#000',
            opacity: 0.3
        },
        toolbar: {
            show: false
        }
    },
    colors: ['#00f0ff', '#ff4d4d'], // Neon-inspired colors
    plotOptions: {
        bar: {
            horizontal: false,
            borderRadius: 8, // Smooth rounded edges
            columnWidth: '45%',
            dataLabels: {
                position: 'top' // Position data labels at the top of bars
            }
        }
    },
    dataLabels: {
        enabled: true,
        style: {
            colors: ['#fff'], // White for better contrast on dark mode
            fontSize: '14px',
            fontWeight: 'bold'
        },
        formatter: (val) => `${val}`
    },
    xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        labels: {
            style: {
                colors: '#A9A9A9', // Light gray for dark mode
                fontSize: '14px',
                fontWeight: 500
            }
        },
        axisBorder: {
            color: '#444'
        },
        axisTicks: {
            color: '#444'
        }
    },
    yaxis: {
        labels: {
            style: {
                colors: '#A9A9A9',
                fontSize: '14px',
                fontWeight: 500
            }
        },
        axisBorder: {
            color: '#444'
        },
        axisTicks: {
            color: '#444'
        }
    },
    legend: {
        position: 'top',
        horizontalAlign: 'right',
        labels: {
            colors: '#A9A9A9' // Light gray for dark mode
        },
        markers: {
            width: 14,
            height: 14,
            radius: 14,
            offsetX: -3
        },
        itemMargin: {
            horizontal: 10,
            vertical: 0
        }
    },
    tooltip: {
        theme: 'dark',
        style: {
            fontSize: '14px'
        },
        y: {
            formatter: (value) => `${value}`
        }
    },
    grid: {
        borderColor: 'rgba(61, 61, 61, 0.1)' // Light grid for dark mode
    }
};

var departmentChart = new ApexCharts(document.querySelector("#departmentChart"), departmentOptions);
departmentChart.render();

    </script>
    
    <script>
     // Attendance Chart (Pie)
        fetch('includes/get_attendance_data.php')
            .then(response => response.json())
            .then(data => {
                var attendanceOptions = {
                    series: [data.present, data.absent],
                    chart: { type: 'pie', height: 370 },
                    labels: ['Present', 'Absent'],
                    colors: ['#FF3B3B', '#9B1BBA'],
                    legend: { position: 'bottom' }
                };
                var attendanceChart = new ApexCharts(document.querySelector("#attendanceChart"), attendanceOptions);
                attendanceChart.render();
            });

        // Evaluation Chart (Bar)
        var evaluationOptions = {
            series: [{ name: 'Evaluation Scores', data: [40, 30, 20, 10] }],
            chart: { type: 'bar', height: 350 },
            colors: ['#FF3B3B'],
            xaxis: { categories: ['Excellent', 'Good', 'Average', 'Poor'] },
            plotOptions: { bar: { borderRadius: 10, horizontal: false } }
        };
        var evaluationChart = new ApexCharts(document.querySelector("#evaluationChart"), evaluationOptions);
        evaluationChart.render();

        // Payslip Chart (Bar)
        var payslipOptions = {
            series: [{ name: 'Generated Payslips', data: [8300, 9200, 11000, 9800, 12000, 8700, 9900, 10500, 11500, 12500, 13500, 14500] }],
            chart: { type: 'bar', height: 350 },
            colors: ['#FF3B3B'],
            xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] },
            plotOptions: { bar: { borderRadius: 8 } }
        };
        var payslipChart = new ApexCharts(document.querySelector("#payslipChart"), payslipOptions);
        payslipChart.render();

        // Report Trends Chart (Area)
        var reportTrendOptions = {
            series: [{ name: 'Report Trends', data: [2300, 3100, 4800, 6000, 7200, 6500, 5400, 5900, 6300, 6800, 7200, 7500] }],
            chart: { type: 'area', height: 350 },
            colors: ['#C70039'],
            xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] },
            stroke: { curve: 'smooth' },
            fill: { type: 'gradient', gradient: { shade: 'dark', type: 'vertical', gradientToColors: ['#9B1BBA'], stops: [0, 100] } }
        };
        var reportTrendChart = new ApexCharts(document.querySelector("#reportTrendChart"), reportTrendOptions);
        reportTrendChart.render();

        fetch('includes/get_attendance_data.php')
            .then(response => response.json())
            .then(data => {
                console.log("Fetched Attendance Data:", data); // Debugging output

                const totalActiveTeachers = data.totalActiveTeachers || 1; // Avoid division by zero
                const presentCount = data.present || 0;
                const absentCount = data.absent || 0;

                // Calculate percentages
                const presentPercentage = ((presentCount / totalActiveTeachers) * 100).toFixed(2);
                const absentPercentage = ((absentCount / totalActiveTeachers) * 100).toFixed(2);

                // Update attendance summary display
                document.getElementById('totalAttendance').textContent = 
                    `Total Active Teachers: ${totalActiveTeachers}, Present: ${presentCount} (${presentPercentage}%), Absent: ${absentCount} (${absentPercentage}%)`;
            })
            .catch(error => console.error("Error fetching attendance data:", error));
        </script>
        
<script>
        document.getElementById('equalsSign').innerHTML = '=';
</script>

<script>

function updateCounts() {
    fetch('includes/dashboard_get_counts.php')
    .then(response => response.json())
    .then(data => {

        
        updateElement('total-staff', data.total_staff.count, data.total_staff.change);
        updateElement('active-teachers', data.active_teachers.count, data.active_teachers.change);
    })
    .catch(error => console.error('Error fetching data:', error));
}

function updateElement(id, count, change) {
    const element = document.getElementById(id);
    const changeElement = document.getElementById(id + '-change');

    if (element && changeElement) {
        let arrow, color, comment;

        if (change > 0) {
            arrow = '▲';
            color = 'green';
            comment = 'Increase';
        } else if (change < 0) {
            arrow = '▼';
            color = 'red';
            comment = 'Decrease';
        } else {
            arrow = '=';
            color = 'gray';
            comment = 'No change';
        }

        element.innerHTML = count;
        changeElement.innerHTML = `<span style="color: ${color}; font-size: 14px;">${arrow} ${Math.abs(change)}% (${comment})</span>`;
    } else {
        console.error(`Element not found: ${id} or ${id}-change`);
    }
}

// Update every second
setInterval(updateCounts, 1000);
updateCounts();
</script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    // Chart for Total Staff
    var totalStaffOptions = {
        chart: {
            type: 'line',
            height: 50,
            sparkline: { enabled: true }
        },
        series: [{ data: totalStaffData }], // Use PHP data
        stroke: { curve: 'smooth', width: 2 },
        colors: ['#007bff'],
        tooltip: { enabled: false }
    };
    new ApexCharts(document.querySelector("#chart-total-staff"), totalStaffOptions).render();

    // Chart for Active Teachers
    var activeTeachersOptions = {
        chart: {
            type: 'line',
            height: 50,
            sparkline: { enabled: true }
        },
        series: [{ data: activeTeachersData }], // Use PHP data
        stroke: { curve: 'smooth', width: 2 },
        colors: ['#28a745'],
        tooltip: { enabled: false }
    };
    new ApexCharts(document.querySelector("#chart-active-teachers"), activeTeachersOptions).render();
});

    // Chart for Pending Evaluations
    var pendingEvaluationsOptions = {
        chart: {
            type: 'bar',
            height: 50,
            sparkline: { enabled: true }
        },
        series: [{ data: [2, 4, 5, 3, 4, 5] }],
        colors: ['#ffc107'],
        tooltip: { enabled: false }
    };
    new ApexCharts(document.querySelector("#chart-pending-evaluations"), pendingEvaluationsOptions).render();

    // Chart for Pending Payroll
    var pendingPayrollOptions = {
        chart: {
            type: 'area',
            height: 50,
            sparkline: { enabled: true }
        },
        series: [{ data: [1, 2, 1, 3, 4, 2] }],
        stroke: { curve: 'smooth', width: 2 },
        colors: ['#dc3545'],
        tooltip: { enabled: false },
        fill: { opacity: 0.3 }
    };
    new ApexCharts(document.querySelector("#chart-pending-payroll"), pendingPayrollOptions).render();
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const currentPage = window.location.pathname.split('/').pop();
        if (currentPage === 'dashboard.php') {
            setDateRange('month'); // ✅ Default to "This Month" only on dashboard
        }
    });

    function setDateRange(range) {
        let startDate, endDate;
        let today = new Date();

        if (range === 15) {
            startDate = new Date(today);
            startDate.setDate(today.getDate() - 15);
            endDate = today;
        } else if (range === 30) {
            startDate = new Date(today);
            startDate.setDate(today.getDate() - 30);
            endDate = today;
        } else if (range === 'month') {
            startDate = new Date(today.getFullYear(), today.getMonth(), 1);
            endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        } else if (range === 'year') {
            startDate = new Date(today.getFullYear(), 0, 1);
            endDate = new Date(today.getFullYear(), 11, 31);
        }

        let formattedStart = startDate.toISOString().split('T')[0];
        let formattedEnd = endDate.toISOString().split('T')[0];

        // ✅ Update Button Label
        let label = range === 15 ? 'Last 15 days' :
                    range === 30 ? 'Last 30 days' :
                    range === 'month' ? 'This Month' :
                    'This Year';

        document.getElementById('dateRangePicker').innerHTML = `<i class="fas fa-calendar-alt"></i> ${label}`;

        console.log(`Selected Range: ${formattedStart} to ${formattedEnd}`);
        // ✅ Send date range to backend using AJAX or form submission if needed
    }
</script>
<script>
                    // When the page loads, wait 3 seconds (for the wave) then add the "dance" class.
                    document.addEventListener("DOMContentLoaded", function(){
                        setTimeout(function(){
                        document.getElementById("stickman").classList.add("dance");
                        }, 2000);
                    });
                    </script>

</body>
</html>
