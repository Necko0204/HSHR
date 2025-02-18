<?php
session_name('admin_session');
session_start();

include 'db_config.php';
include 'helper.php';

$sender_id = $_SESSION['admin_id'];
$sender_role = $_SESSION['position'];


if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$query = "SELECT COUNT(id) AS total_staff FROM employees";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$total_staff = $row['total_staff'];

$query = "SELECT COUNT(employee_id) AS active_teachers FROM staff_accounts WHERE status = 'active'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$active_teachers = $row['active_teachers'];
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
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="background.css">
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
                <!-- Card with Falling Stars, Photo, Pulsing Icon, and Time -->
                <div class="card shadow-lg mb-4 d-flex flex-column align-items-center text-center"
                    style="margin: 20px auto; max-width: 5000px; padding: 30px; border-radius: 15px; position: relative;">
                    <div class="stars"></div>

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
                                Hello, <span id="greeting"></span> <?php echo isset($userData['name']) ? $userData['name'] : 'Guest'; ?>!
                            </h5>

                            <h6><?php echo isset($userData['position']) ? $userData['position'] : 'Guest'; ?></h6>
                            <p class="time-container" id="ph-time"></p>
                        </div>
                    </div>
            </section>
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="custom-card">
                        <div class="card-body">
                            <h5 class="card-title">Total Staff</h5>
                            <h3><?php echo $total_staff; ?></h3>  <!-- Dynamic total staff count -->
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="custom-card">
                        <div class="card-body">
                            <h5 class="card-title">Active Teachers</h5>
                            <h3><?php echo $active_teachers; ?></h3>  <!-- Dynamic active teachers count -->
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="custom-card">
                        <div class="card-body">
                            <h5 class="card-title">Pending Evaluations</h5>
                            <h3>15</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="custom-card">
                        <div class="card-body">
                            <h5 class="card-title">Pending Payroll</h5>
                            <h3>5</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="custom-card gradient-card">
                        <div class="card-body">
                            <h5 class="card-title text-black">Staff Performance Over Time</h5>
                            <div class="chart-container">
                                <canvas id="performanceChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="custom-card gradient-card">
                        <div class="card-body">
                            <h5 class="card-title text-black">Department Growth Trends</h5>
                            <div class="chart-container">
                                <canvas id="departmentChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </main>
            </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="background.js"></script>
    <script>
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
        const options = { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        const now = new Date().toLocaleTimeString('en-US', options);
        document.getElementById('ph-time').textContent = now;

        const hour = new Date().getHours();
        let greeting = "";
        if (hour >= 7 && hour < 12) {
            greeting = "Good Morning";
        } else if (hour >= 12 && hour < 17) {
            greeting = "Good Afternoon";
        } else {
            greeting = "Good Evening";
        }
        document.getElementById('greeting').textContent = greeting;
    }
    setInterval(updateTime, 1000);
    updateTime();
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

    var ctx1 = document.getElementById('performanceChart').getContext('2d');
        var performanceChart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Average Performance Score',
                    data: [75, 80, 85, 90, 95, 92, 96],
                    borderColor: '#000000',
                    backgroundColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 3,
                    tension: 0.4, 
                    pointBackgroundColor: '#ffc0cb',
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true, // Prevents infinite height
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(99, 71, 71, 0.1)' },
                        ticks: { color: 'black' }
                    },
                    y: {
                        grid: { color: 'rgba(99, 71, 71, 0.1)'  },
                        ticks: { color: 'black' }
                    }
                }
            }
        });

        var ctx2 = document.getElementById('departmentChart').getContext('2d');
        var departmentChart = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'New Employees Per Department',
                    data: [5, 10, 15, 8, 12, 18, 20],
                    borderColor: '#000000',
                    backgroundColor: 'rgba(255, 235, 59, 0.1)',
                    borderWidth: 3,
                    tension: 0.4, 
                    pointBackgroundColor: '#ffeb3b',
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true, // Prevents infinite height
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(99, 71, 71, 0.1)' },
                        ticks: { color: 'black' }
                    },
                    y: {
                        grid: { color: 'rgba(99, 71, 71, 0.1)'  },
                        ticks: { color: 'black' }
                    }
                }
            }
        });
    </script>
</body>
</html>
