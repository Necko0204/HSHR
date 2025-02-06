<?php
session_name('admin_session');
session_start();

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
    <title>HR Management - School</title>
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
            background: #f8f9fa;
            border: none;
            color: black;
            transition: transform 0.3s ease-in-out;
        }
        .card:hover {
            transform: scale(1.05);
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
        }
        .pulsing-icon {
            width: 12px;
            height: 12px;
            background-color: green;
            border-radius: 50%;
            position: absolute;
            bottom: 0;
            right: 0;
            transform: translate(25%, 25%);
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.5); opacity: 0.6; }
            100% { transform: scale(1); opacity: 1; }
        }
        .time-container {
            font-size: 16px;
            color: black;
            font-weight: bold;
            text-align: center;
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
                <div class="card shadow-lg mb-4 position-relative d-flex align-items-center" style="margin-top: 20px; width: 100%; height: 200px; padding: 20px;">
                    <div class="stars"></div>
                    <div class="profile-container position-relative me-3">
                        <img src="photo.jpg" alt="Profile" class="rounded-circle" width="50" height="50">
                        <div class="pulsing-icon"></div>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h5 class="card-title">Hello, <span id="greeting"></span>!</h5>
                        <h6 class="text-center">Live Time (Philippines)</h6>
                        <p class="time-container text-center" id="ph-time"></p>
                    </div>
                </div>
        </section>


        <section class="content" data-aos="fade-right">
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h5 class="card-title">Total Staff</h5>
                            <h3>120</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h5 class="card-title">Active Teachers</h5>
                            <h3>80</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h5 class="card-title">Pending Evaluations</h5>
                            <h3>15</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm text-center">
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
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Staff Performance</h5>
                            <canvas id="performanceChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Staff Department Distribution</h5>
                            <canvas id="departmentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
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
    const options = { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', second: '2-digit' };
    document.getElementById('ph-time').textContent = new Date().toLocaleTimeString('en-US', options);
}
setInterval(updateTime, 1000);
updateTime();
</script>
    <script>
        AOS.init({
            duration: 1200,
            once: true,
        });

        var ctx1 = document.getElementById('performanceChart').getContext('2d');
        var performanceChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Teacher 1', 'Teacher 2', 'Teacher 3', 'Teacher 4', 'Teacher 5'],
                datasets: [{
                    label: 'Performance Scores',
                    data: [85, 78, 92, 88, 76],
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#e74a3b', '#f6c23e'],
                }]
            }
        });

        var ctx2 = document.getElementById('departmentChart').getContext('2d');
        var departmentChart = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['Mathematics', 'Science', 'English', 'History', 'Art'],
                datasets: [{
                    data: [30, 25, 15, 20, 10],
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#e74a3b', '#f6c23e'],
                }]
            }
        });
    </script>
</body>
</html>
