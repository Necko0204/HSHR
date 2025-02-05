<?php
session_start();
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
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <main class="wrapper">
        <section class="content" data-aos="fade-left">
            <div class="card shadow-lg mb-4">
                <div class="card-body">
                    <h5 class="card-title">Welcome to HR Dashboard</h5>
                    <p class="card-text">Manage school staff, track performance, and handle HR tasks such as payroll, evaluations, and more.</p>
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
