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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="background.css">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<body>
<!-- Sidebar & Navbar in a separate container -->
<div class="main-container">
        <?php include 'sidebar.php'; ?>
        </div>
        <div class="content-container">
            <?php include 'nav_header.php'; ?>
            </div>
    <main class="wrapper">
            <div class="d-flex justify-content-start align-items-center"></div>
    

<!-- New Settings Section -->
<div class="card mb-4 shadow-sm border-0 rounded-lg">
    <div class="card-header bg-secondary text-white fw-bold text-center">
        Find the setting you need
    </div>
    <div class="card-body">
        <!-- Search Settings -->
        <form method="POST" action="">
            <input type="text" class="form-control mb-3" placeholder="Search settings" name="search">
        </form>
        
        <h5 class="fw-bold">Most visited settings</h5>
        <div class="row g-3">
            <div class="col-md-6 col-lg-4">
                <div class="card text-center p-4 shadow-sm border-0 rounded-lg">
                    <span class="fs-2">📜</span>
                    <h6 class="fw-bold mt-2">Activity Log</h6>
                    <p class="text-muted">View and manage your activity on the platform.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card text-center p-4 shadow-sm border-0 rounded-lg">
                    <span class="fs-2">💡</span>
                    <h6 class="fw-bold mt-2">Dark Mode</h6>
                    <p class="text-muted">Choose if you want to use dark mode.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <a href="view_profile.php" class="text-decoration-none">
                    <div class="card text-center p-4 shadow-sm border-0 rounded-lg">
                        <span class="fs-2">👤</span>
                        <h6 class="fw-bold mt-2">View Your Profile</h6>
                        <p class="text-muted">Access and edit your profile information.</p>
                    </div>
                </a>
            </div>
        </div>
        
        <h5 class="fw-bold mt-4">Looking for something else?</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card p-4 shadow-sm border-0 rounded-lg">
                    <h6 class="fw-bold">Privacy Center</h6>
                    <p class="text-muted">Learn how to manage and control your privacy across our products.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-4 shadow-sm border-0 rounded-lg">
                    <h6 class="fw-bold">Help Center</h6>
                    <p class="text-muted">Learn more about our updated settings experience.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms and Services Card -->
<div class="terms-card text-center">
    <h5 class="fw-bold">Terms and Services</h5>
    <p>By using this platform, you agree to our terms and services. Please read them carefully to understand your rights and responsibilities.</p>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
