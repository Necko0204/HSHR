<?php
require_once __DIR__ . '/includes/admin_page.php';
include 'includes/breadcrumb.php';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

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
            <div class="d-flex justify-content-start align-items-center"></div>


<!-- New Settings Section -->
<div class="card mb-4 shadow-sm border-1 rounded-lg">
    <div class="card-header fw-bold text-center">
     Settings
    </div>
    <div class="card-body">
        <!-- Search Settings -->
            <input type="text" class="form-control mb-3" placeholder="Search settings" name="search">


        <h5 class="fw-bold">Most visited settings</h5>
        <div class="row g-3">
            <div class="col-md-6 col-lg-4">
                <a href="evaluation_form.php" target="_blank" class="text-decoration-none">
                    <div class="card text-center p-4 shadow-sm border-1 rounded-lg">
                        <span class="fs-2">📜</span>
                        <h6 class="fw-bold mt-2">Evaluation Form</h6>
                        <p class="text-muted">View and manage the evaluation form of the hard working teachers.</p>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-4">
                <a href="employment_application_page.php" target="_blank" class="text-decoration-none">
                    <div class="card text-center p-4 shadow-sm border-1 rounded-lg">
                        <span class="fs-2">💡</span>
                        <h6 class="fw-bold mt-2">Application Page</h6>
                        <p class="text-muted">View and manage the Application form of the aspiring teachers.</p>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-4">
                <a href="view_profile.php" class="text-decoration-none">
                    <div class="card text-center p-4 shadow-sm border-1 rounded-lg">
                        <span class="fs-2">👤</span>
                        <h6 class="fw-bold mt-2">View Your Profile</h6>
                        <p class="text-muted">Access and edit your profile information for visual state.</p>
                    </div>
                </a>
            </div>
        </div>

        <h5 class="fw-bold mt-4">Looking for something else?</h5>
        <div class="row g-3">
            <div class="col-md-6">
            <a href="salary_agreement.php" target="_blank" class="text-decoration-none">
                <div class="card p-4 shadow-sm border-1 rounded-lg text-center">
                <span class="fs-2">💰</span>
                <h6 class="fw-bold mt-2">Salary Contract</h6>
                <p class="text-muted">View and manage your salary contract details and agreements.</p>
                </div>
            </a>
            </div>
            <div class="col-md-6">
            <div class="card p-4 shadow-sm border-1 rounded-lg text-center">
                <span class="fs-2">❓</span>
                <h6 class="fw-bold mt-2">Help Center</h6>
                <p class="text-muted">Learn more about our updated settings experience.</p>
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
    </div>


        <!-- Terms and Services Modal -->
        <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header position-relative">
        <div class="w-100 text-center">
            <div class="d-inline-flex align-items-center">
            <img src="images/asdasdasd123123123123123.jpg" alt="School Logo" class="logo me-2">
            <h5 class="modal-title mb-0" id="termsModalLabel">Terms and Services</h5>
            </div>
        </div>
        <button type="button" class="btn-close position-absolute top-0 end-0 m-2" data-bs-dismiss="modal" aria-label="Close"></button>
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
                <div class="text-center">
                    <a href="terms-and-conditions.php" target="_blank" class="terms-button">Read Full Terms</a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                <i class="fas fa-times"></i> Close
                </button>
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
</script>
</body>
</html>
