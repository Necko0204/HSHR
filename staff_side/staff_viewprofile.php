<?php
require_once __DIR__ . '/includes/staff_session.php';

error_reporting(E_ALL);
ini_set('display_errors', '0');

include 'staff_helper.php';
include 'db_config.php';

if (!isset($_SESSION['employee_id']) || !in_array(strtolower($_SESSION['role'] ?? ''), ['staff', 'intern'], true)) {
    header("Location: index.php");
    exit();
}

$staffData['profile_picture'] = hshr_profile_picture_url($staffData['profile_picture'] ?? null, '../');
foreach ($staffData as $key => $value) {
    if (is_string($value)) $staffData[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
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
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body style="overflow: hidden;">



<div class="main-container">
        <?php include 'staff_navbar.php'; ?>
    </div>
<!-- Back to Dashboard Button -->
<div style="position: absolute; top: 7px; left: 20px; z-index: 1000;">
    <a href="staff_settings.php" class="btn btn-secondary">
        <i class="fa fa-arrow-left"></i> Back to Dashboard
    </a>
</div>




<!-- Animated Box Shapes -->
<div class="animation-container">
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
</div>

<main class="wrapper" id="mainWrapper">
    <div class="row justify-content-center align-items-center" style="min-height: 90vh;">
        <div class="col-lg-7 col-md-9">
            <div class="profile-card shadow-lg border-0 rounded-5 p-5 bg-white position-relative overflow-hidden" id="profileCard" style="backdrop-filter: blur(2px);">
                <!-- Decorative floating circles -->
                <div class="floating-circle position-absolute" style="top: -40px; left: -40px; width: 80px; height: 80px; background: rgba(13,110,253,0.08); border-radius: 50%; z-index: 0;"></div>
                <div class="floating-circle position-absolute" style="bottom: -30px; right: -30px; width: 60px; height: 60px; background: rgba(13,110,253,0.10); border-radius: 50%; z-index: 0;"></div>
                <div class="floating-circle position-absolute" style="top: 30px; right: 40px; width: 30px; height: 30px; background: rgba(13,110,253,0.07); border-radius: 50%; z-index: 0;"></div>
                <!-- Profile image with subtle shadow and border -->
                <div class="profile-header text-center position-relative z-1">
                    <div class="profile-image-container d-inline-block mb-3 position-relative">
                        <img src="<?= $staffData['profile_picture'] ?>"
                             alt="Profile Picture"
                             class="profile-picture2 rounded-circle shadow border border-4 border-primary"
                             style="width: 150px; height: 150px; object-fit: cover; background: #f8f9fa;">
                        <span class="position-absolute bottom-0 end-0 translate-middle p-2 bg-white rounded-circle border shadow-sm" style="font-size: 1.2rem;">
                            <i class="fa fa-user text-primary"></i>
                        </span>
                    </div>
                    <h2 class="mt-2 mb-0 text-primary fw-bold" style="letter-spacing: 1px;">
                        <?php echo isset($staffData['firstname']) ? $staffData['firstname'] . ' ' . $staffData['lastname'] : 'Unknown'; ?>
                    </h2>
                    <p class="text-muted mb-2" style="font-size: 1.1rem; letter-spacing: 0.5px;">
                        <?php if (isset($staffData['role'])): ?>
                            <span class="badge bg-primary ms-2"><?php echo htmlspecialchars($staffData['role']); ?></span>
                        <?php endif; ?>
                    </p>
                </div>
                <!-- Profile details -->
                <div class="row mt-4 g-4">
                    <div class="col-md-6">
                        <div class="p-3 rounded-4 bg-light border-0 shadow-sm mb-3 d-flex align-items-center">
                            <i class="fa fa-user-circle fa-lg text-primary me-3"></i>
                            <div>
                                <div class="fw-semibold text-secondary small">Full Name</div>
                                <div class="text-dark"><?php echo isset($staffData['firstname']) ? $staffData['firstname'] . ' ' . $staffData['lastname'] : 'N/A'; ?></div>
                            </div>
                        </div>
                        <div class="p-3 rounded-4 bg-light border-0 shadow-sm mb-3 d-flex align-items-center">
                            <i class="fa fa-envelope fa-lg text-primary me-3"></i>
                            <div>
                                <div class="fw-semibold text-secondary small">Email Address</div>
                                <div class="text-dark"><?php echo isset($staffData['email1']) ? $staffData['email1'] : 'N/A'; ?></div>
                            </div>
                        </div>
                        <div class="p-3 rounded-4 bg-light border-0 shadow-sm d-flex align-items-center">
                            <i class="fa fa-home fa-lg text-primary me-3"></i>
                            <div>
                                <div class="fw-semibold text-secondary small">Home Address</div>
                                <div class="text-dark"><?php echo isset($staffData['homeaddress']) ? $staffData['homeaddress'] : 'N/A'; ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-4 bg-light border-0 shadow-sm mb-3 d-flex align-items-center">
                            <i class="fa fa-phone fa-lg text-primary me-3"></i>
                            <div>
                                <div class="fw-semibold text-secondary small">Contact Number</div>
                                <div class="text-dark"><?php echo isset($staffData['mobilephone']) ? $staffData['mobilephone'] : 'N/A'; ?></div>
                            </div>
                        </div>
                        <div class="p-3 rounded-4 bg-light border-0 shadow-sm mb-3 d-flex align-items-center">
                            <i class="fa fa-check-circle fa-lg text-primary me-3"></i>
                            <div>
                                <div class="fw-semibold text-secondary small">Status</div>
                                <div class="text-dark"><?php echo isset($staffData['status']) ? $staffData['status'] : 'N/A'; ?></div>
                            </div>
                        </div>
                        <div class="p-3 rounded-4 bg-light border-0 shadow-sm d-flex align-items-center">
                            <i class="fa fa-heart fa-lg text-primary me-3"></i>
                            <div>
                                <div class="fw-semibold text-secondary small">Marital Status</div>
                                <div class="text-dark"><?php echo isset($staffData['maritalstatus']) ? $staffData['maritalstatus'] : 'N/A'; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-5">
                    <button type="button" class="btn btn-primary px-5 py-2 fw-bold shadow rounded-pill" id="editProfileButton" style="font-size: 1.1rem;">
                        <i class="fa fa-edit me-2"></i> Edit Profile
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>



<!-- Profile Edit Card -->
<div class="profile-edit-card shadow-lg border-0 rounded-5 p-5 bg-white position-relative overflow-hidden" id="profileEditCard" style="display: none; max-width: 700px; margin: 40px auto;">
    <!-- Decorative floating circles for edit card -->
    <div class="floating-circle position-absolute" style="top: -35px; left: -35px; width: 70px; height: 70px; background: rgba(13,110,253,0.09); border-radius: 50%; z-index: 0;"></div>
    <div class="floating-circle position-absolute" style="bottom: -25px; right: -25px; width: 50px; height: 50px; background: rgba(13,110,253,0.12); border-radius: 50%; z-index: 0;"></div>
    <form id="updateForm" method="POST" class="mt-3 position-relative z-1" enctype="multipart/form-data" autocomplete="off">
        <?= hshr_csrf_field() ?>

        <div class="text-center mb-4">
            <div class="position-relative d-inline-block">
                <img id="profilePicture"
                    src="<?= $staffData['profile_picture'] ?>"
                    alt="Profile Picture"
                    class="rounded-circle border profile-picture2 shadow"
                    style="width: 130px; height: 130px; object-fit: cover; border: 4px solid #0d6efd; background: #f8f9fa;">
                <label class="position-absolute bottom-0 end-0 bg-white border border-2 border-primary rounded-circle p-2 shadow-sm" style="cursor: pointer; font-size: 1.2rem;">
                    <i class="fa fa-camera text-primary"></i>
                    <input type="file" id="profilePicInput" name="profile_picture" accept="image/*" style="display: none;" onchange="previewImage(event)">
                </label>
            </div>
            <h4 class="mt-3 mb-1 text-primary fw-bold" style="letter-spacing: 1px;"><?php echo isset($staffData['firstname']) ? $staffData['firstname'] . ' ' . $staffData['lastname'] : 'Unknown'; ?></h4>
            <p class="text-muted mb-0" style="font-size: 1.05rem;"><?php echo isset($staffData['email1']) ? $staffData['email1'] : 'No email provided'; ?></p>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label fw-semibold text-secondary">First Name</label>
                <input type="text" name="firstname" class="form-control rounded-4 text-dark bg-light border-0 shadow-sm" value="<?php echo isset($staffData['firstname']) ? $staffData['firstname'] : ''; ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold text-secondary">Last Name</label>
                <input type="text" name="lastname" class="form-control rounded-4 text-dark bg-light border-0 shadow-sm" value="<?php echo isset($staffData['lastname']) ? $staffData['lastname'] : ''; ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold text-secondary">Email Address</label>
                <input type="email" name="email1" class="form-control rounded-4 text-dark bg-light border-0 shadow-sm" value="<?php echo isset($staffData['email1']) ? $staffData['email1'] : ''; ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold text-secondary">Home Address</label>
                <textarea name="homeaddress" class="form-control rounded-4 text-dark bg-light border-0 shadow-sm" rows="2" required><?php echo isset($staffData['homeaddress']) ? $staffData['homeaddress'] : ''; ?></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold text-secondary">Contact Number</label>
                <input type="text" name="mobilephone" class="form-control rounded-4 text-dark bg-light border-0 shadow-sm" value="<?php echo isset($staffData['mobilephone']) ? $staffData['mobilephone'] : ''; ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold text-secondary">Marital Status</label>
                <select name="maritalstatus" class="form-select rounded-4 text-dark bg-light border-0 shadow-sm" required>
                    <option value="Single" <?php echo (isset($staffData['maritalstatus']) && $staffData['maritalstatus'] == 'Single') ? 'selected' : ''; ?>>Single</option>
                    <option value="Married" <?php echo (isset($staffData['maritalstatus']) && $staffData['maritalstatus'] == 'Married') ? 'selected' : ''; ?>>Married</option>
                    <option value="Divorced" <?php echo (isset($staffData['maritalstatus']) && $staffData['maritalstatus'] == 'Divorced') ? 'selected' : ''; ?>>Divorced</option>
                    <option value="Widowed" <?php echo (isset($staffData['maritalstatus']) && $staffData['maritalstatus'] == 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-5 gap-3">
            <button type="submit" class="btn btn-primary w-50 py-2 fw-bold rounded-pill shadow-sm"><i class="fa fa-save me-2"></i>Save</button>
            <button type="button" class="btn btn-outline-secondary w-50 py-2 fw-bold rounded-pill shadow-sm" id="cancelEditProfile"><i class="fa fa-times me-2"></i>Cancel</button>
        </div>
    </form>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.js"></script>
<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('profilePicture');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>
<script>
    document.getElementById('editProfileButton').addEventListener('click', function() {
        document.getElementById('mainWrapper').style.display = 'none';
        document.getElementById('profileEditCard').style.display = 'block';
    });

    document.getElementById('cancelEditProfile').addEventListener('click', function() {
        document.getElementById('profileEditCard').style.display = 'none';
        document.getElementById('mainWrapper').style.display = 'block';
    });
</script>
<script>
$("#updateForm").submit(function (event) {
    event.preventDefault();

    let formData = new FormData(this);

    $.ajax({
        url: "update_profile.php", // Ensure this points to the correct PHP file
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            try {
                response = typeof response === "string" ? JSON.parse(response) : response;
            } catch (error) {
                console.error("Invalid JSON response", error);
                return Swal.fire("Error!", "Unexpected server response.", "error");
            }

            if (response.status === "success") {
                if (response.profile_picture) {
                    $("#profilePicture").attr("src", response.profile_picture);
                }

                Swal.fire({
                    title: "Success!",
                    text: response.message,
                    icon: "success",
                    confirmButtonText: "Ok"
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload(); // Reload the page after successful update
                    }
                });
            } else {
                Swal.fire({
                    title: "Error!",
                    text: response.message,
                    icon: "error",
                    confirmButtonText: "Try Again"
                });
            }
        },
        error: function () {
            Swal.fire("Error!", "An error occurred during the upload. Please try again.", "error");
        }
    });
});

</script>
</body>
</html>
