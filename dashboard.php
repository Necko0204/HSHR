<?php
session_name('admin_session');
session_start();

include 'db_config.php';
include 'helper.php';

// Debug: Check if session is properly set
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="background.css">
    <!-- AOS CSS (Animate on Scroll) -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: white;
            color: black;
            min-height: 100vh;
        }
        .wrapper {
            padding: 30px;
        }
        .card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        .shadow-lg {
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1) !important;
        }
        canvas {
            max-width: 100%;
            height: auto;
        }
        .stars {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
            top: 0;
            left: 0;
        }
        .star {
            position: absolute;
            width: 10px;
            height: 10px;
            background: black;
            border-radius: 50%;
            opacity: 0.8;
            animation: fall linear infinite;
        }
        @keyframes fall {
            from { transform: translateY(-100px); opacity: 1; }
            to { transform: translateY(300px); opacity: 0; }
        }
        .profile-container {
        position: relative;
        display: inline-block;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        }

        .profile-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            display: block;
        }

        .status-indicator {
            position: absolute;
            bottom: 10px; /* Adjusted to be inside */
            right: 10px; /* Adjusted to be inside */
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
        }

        .pulsing-icon {
            width: 12px;
            height: 12px;
            background-color: green;
            border-radius: 50%;
            position: relative; /* Changed to relative */
            z-index: 2; /* Keeps it above the ring */
            top: -0.5px; /* Moves the icon up */
            left: -0.5px; /* Moves the icon to the left */
        }

        .pulsing-ring {
            position: absolute;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 2px solid rgba(0, 128, 0, 0.5);
            animation: pulse-ring 1.5s infinite;
        }

        @keyframes pulse-ring {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.5); opacity: 0.5; }
            100% { transform: scale(2); opacity: 0; }
        }


        .time-container {
            font-size: 16px;
            color: black;
            font-weight: bold;
            text-align: center;
        }
        /* Base Card Styling */
        .custom-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /* Hover Effect */
        .custom-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        .gradient-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

    .chart-container {
        position: relative;
        height: 250px; /* Fix chart height */
        width: 100%;
    }

    </style>
</head>
<body>


   <!-- Sidebar & Navbar-->
    <?php include 'sidebar.php'; ?>
    <?php include 'nav_header.php'; ?>

    <!-- Main Content Wrapper -->
    <main class="wrapper">
        <section class="content" data-aos="fade-left">
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


        <section class="content" data-aos="fade-right">
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="custom-card">
                        <div class="card-body">
                            <h5 class="card-title">Total Staff</h5>
                            <h3>120</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="custom-card">
                        <div class="card-body">
                            <h5 class="card-title">Active Teachers</h5>
                            <h3>80</h3>
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
        </section>

        <section class="content" data-aos="fade-up">
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
        </section>
    </main>

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
