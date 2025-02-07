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
    <title>Leave Requests</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="background.css">
</head>
<style>
        body {
            font-family: 'Poppins', sans-serif;
            background: white;
            color: black;
            min-height: 100vh;
            margin: 0; /* Reset margin to ensure no unwanted space */
        }
        /* Wrapper for main content */
        .wrapper {
            margin-left: 270px; /* Adjust based on your sidebar width */
            padding: 20px;
            max-width: calc(100% - 270px);
        }
                    /* Content section styling */
        .content {
            margin-top: 20px; /* Ensure spacing between navbar and content */
        }

        /* Fix alignment on smaller screens */
        @media (max-width: 768px) {
            .wrapper {
                margin-left: 0;
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .container {
                margin-left: 0;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding: 10px;
            }
        }

        .card-header {
            background-color: #6c757d;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease-in-out;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.15);
        }

        .card-body {
            background: linear-gradient(145deg, #ffffff, #e6e6e6);
            padding: 30px;
        }

        .card .fs-3 {
            transition: transform 0.3s ease;
        }

        .card .fs-3:hover {
            transform: scale(1.2);
        }

        .card .fw-bold {
            margin-top: 10px;
            font-size: 1.1rem;
            color: #343a40;
        }

        .card p {
            color: #6c757d;
        }

        .row .col-md-4 {
            margin-bottom: 20px;
        }

        .card input[type="text"] {
            border-radius: 25px;
            border: 1px solid #ced4da;
            padding: 15px;
            font-size: 16px;
            transition: 0.3s;
        }

        .card input[type="text"]:focus {
            outline: none;
            border-color: #80bdff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.25);
        }



        .section-heading {
            font-size: 1.3rem;
            font-weight: bold;
            color: #495057;
            margin-top: 30px;
        }

        .card-footer {
            background-color: #f1f1f1;
            padding: 10px;
            text-align: center;
        }

        .card-footer a {
            color: #007bff;
            text-decoration: none;
        }

        .card-footer a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 1200px;
        }
         /* Terms and Services Card */
         .terms-card {
            background-color: #fff;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-top: 50px;
        }

        .terms-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .terms-card h5 {
            font-size: 1.5rem;
            color: #343a40;
        }

        .terms-card p {
            color: #6c757d;
            font-size: 1rem;
            margin-bottom: 20px;
        }

        .terms-card a {
            color: #007bff;
            font-weight: bold;
            text-decoration: none;
        }

        .terms-card a:hover {
            text-decoration: underline;
        }

      /* Modal Styles */
.modal-content {
    border-radius: 10px;
    box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
}

/* Header */
.modal-header {
    background: linear-gradient(135deg, #ff0000, #000000);
    color: white;
    padding: 15px;
    border-bottom: none;
    border-radius: 10px 10px 0 0;
}

/* Body */
.modal-body {
    text-align: left;
    max-height: 400px;
    overflow-y: auto;
    padding: 20px;
    font-size: 16px;
}

/* Footer */
.modal-footer {
    background-color: #f8f9fa;
    border-top: none;
    border-radius: 0 0 10px 10px;
    padding: 10px;
}

/* Logo */
.logo {
    width: 50px;
    height: auto;
}

/* Buttons */
.btn-outline-primary {
    border-radius: 20px;
    transition: 0.3s;
}

.btn-outline-primary:hover {
    background: #007bff;
    color: white;
}


    </style>
<body>
    <!-- Sidebar & Navbar-->
    <?php include 'sidebar.php'; ?>
    <?php include 'nav_header.php'; ?>

    <main class="wrapper">
            <section class="content">
            <div class="d-flex justify-content-start align-items-center"></div>
              
            </div>
            </section>

     <!-- New Settings Section -->
     <div class="card mb-4 shadow-sm border-0 rounded-lg">
        <div class="card-header bg-secondary text-white">
            Find the setting you need
        </div>
        <div class="card-body">
            <!-- Search Settings -->
            <form method="POST" action="">
                <input type="text" class="form-control mb-3" placeholder="Search settings" name="search">
            </form>
            
            <h5>Most visited settings</h5>
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-center p-3 shadow-sm border-0 rounded-lg">
                        <span class="fs-3">📜</span>
                        <h6 class="fw-bold">Activity log</h6>
                        <p class="text-muted">View and manage your activity on the platform.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center p-3 shadow-sm border-0 rounded-lg">
                        <span class="fs-3">💡</span>
                        <h6 class="fw-bold">Dark mode</h6>
                        <p class="text-muted">Choose if you want to use dark mode.</p>
                    </div>
                </div>
            </div>
            
            <h5 class="mt-4">Looking for something else?</h5>
            <div class="card p-3 shadow-sm border-0 rounded-lg">
                <h6 class="fw-bold">Privacy Center</h6>
                <p class="text-muted">Learn how to manage and control your privacy across our products.</p>
            </div>
            <div class="card p-3 mt-2 shadow-sm border-0 rounded-lg">
                <h6 class="fw-bold">Help Center</h6>
                <p class="text-muted">Learn more about our updated settings experience.</p>
            </div>
        </div>
    </div>
     <!-- Terms and Services Card -->
     <div class="terms-card">
        <h5>Terms and Services</h5>
        <p>By using this platform, you agree to our terms and services. Please read them carefully to understand your rights and responsibilities. We value your privacy and security, so please ensure you are familiar with our policies.</p>
        <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Read our Terms and Services</a>
    </div>

   <!-- Terms and Services Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center">
                    <img src="images/asdasdasd123123123123123.jpg" alt="School Logo" class="logo me-2">
                    <h5 class="modal-title mb-0" id="termsModalLabel">Terms and Services</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="text-primary fw-bold">School Policy - Terms and Conditions</h6>
                <p>Welcome to our school platform. By accessing or using our services, you agree to the following terms:</p>
                <ul class="list-unstyled">
                    <li><i class="bi bi-check-circle-fill text-success"></i> <strong>Use of Service:</strong> You must be a Administrator or a staff member to use this platform.</li>
                    <li><i class="bi bi-shield-lock-fill text-danger"></i> <strong>Privacy:</strong> We value your privacy and will not share your personal information without consent.</li>
                    <li><i class="bi bi-book-fill text-warning"></i> <strong>Academic Integrity:</strong> All academic materials must follow the school’s code of conduct.</li>
                    <li><i class="bi bi-people-fill text-info"></i> <strong>Respectful Behavior:</strong> Disrespectful behavior towards others is not tolerated.</li>
                </ul>
                <p class="mt-3">By using our services, you acknowledge and agree to these terms. For more details, read the full policy.</p>
                <a href="terms-and-conditions.html" target="_blank" class="btn btn-outline-primary">Read Full Terms</a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- PHP Script to Trigger Modal -->
<?php
if (isset($_GET['show_terms'])) {
    echo "<script>document.addEventListener('DOMContentLoaded', function() { var myModal = new bootstrap.Modal(document.getElementById('termsModal')); myModal.show(); });</script>";
}
?>

</main>
    <script src="background.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
