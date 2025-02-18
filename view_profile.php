<?php
session_name('admin_session');
session_start();

include 'helper.php';

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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
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
    <div class="profile-card text-center p-4 shadow-lg rounded" id="profileCard">
        <div class="profile-header">
            <div class="profile-image-container2">
                <img src="<?php echo !empty($userData['profile_picture']) ? $userData['profile_picture'] : 'uploads/profile_pictures/default.jpg'; ?>" 
                    alt="Profile Picture" 
                    class="profile-picture2">
            </div>
            <h3 style="margin: 20px 0;"><?php echo htmlspecialchars($userData['name']); ?> </h3>
        </div>

            <div class="profile-details-container">
                <div class="profile-detail">
                    <strong>Position</strong>
                    <span><?php echo htmlspecialchars($userData['position']); ?></span>
                </div>
                <div class="profile-detail">
                    <strong>Username</strong>
                    <span><?php echo htmlspecialchars($userData['username']); ?></span>
                </div>
                <div class="profile-detail">
                    <strong>Age</strong>
                    <span><?php echo htmlspecialchars($userData['age']); ?></span>
                </div>
                <div class="profile-detail">
                    <strong>Email</strong>
                    <span><?php echo htmlspecialchars($userData['email']); ?></span>
                </div>
                <div class="profile-detail">
                    <strong>Phone</strong>
                    <span><?php echo htmlspecialchars($userData['phone'] ?? 'N/A'); ?></span>
                </div>
                <div class="profile-detail">
                    <strong>Address</strong>
                    <span><?php echo htmlspecialchars($userData['address'] ?? 'N/A'); ?></span>
                </div>
                <div class="profile-detail full-width">
                    <strong>Bio</strong>
                    <p><?php echo htmlspecialchars($userData['bio']); ?></p>
                </div>
                <div class="profile-detail full-width">
                    <strong>Experience</strong>
                    <p><?php echo htmlspecialchars($userData['experiences'] ?? 'Not provided'); ?></p>
                </div>
            </div>
            <button class="btn2 update-profile-btn" id="editProfileBtn">Update Profile</button>
        </div>
</main>
    <div id="updateProfileCard" class="hidden">
        <h3>Edit Profile</h3>
        <form action="update_profile.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $userData['id']; ?>">

            <!-- Profile Picture Section -->
            <div class="profile-picture-container2">
                <div class="profile-picture2">
                    <img id="profilePreview" src="<?php echo !empty($userData['profile_picture']) ? $userData['profile_picture'] : 'uploads/profile_pictures/default.jpg'; ?>" alt="Profile Picture">
                    <input type="file" id="profilePicInput" name="profile_picture" accept="image/*" style="display: none;">
                </div>
                <label class="form-label">Profile Picture</label>
            </div>

            <!-- Form Fields Section -->
            <div class="form-container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($userData['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Position</label>
                    <input type="text" class="form-control" name="position" value="<?php echo htmlspecialchars($userData['position']); ?>" required readonly>
                </div>

                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($userData['username']); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Age</label>
                    <input type="number" class="form-control" name="age" value="<?php echo htmlspecialchars($userData['age']); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Bio</label>
                    <textarea class="form-control" name="bio" required><?php echo htmlspecialchars($userData['bio']); ?></textarea>
                </div>

                <!-- New Fields -->
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($userData['phone']); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Experiences</label>
                    <textarea class="form-control" name="experiences" required><?php echo htmlspecialchars($userData['experiences']); ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea class="form-control" name="address" required><?php echo htmlspecialchars($userData['address']); ?></textarea>
                </div>
            </div>
            <!-- Buttons Section -->
            <div class="btn2-container">
                <button type="button" class="btn2 btn-secondary" id="cancelEdit">Cancel</button>
                <button type="submit" class="btn2 btn-success">Save Changes</button>
            </div>
        </form>
    </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="background.js"></script>
<script>
    $(document).ready(function () {
        // Toastr configuration for smooth alerts
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "1000", // Show alert for 3 seconds
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Get form elements
        const profileCard = $("#profileCard");
        const updateProfileCard = $("#updateProfileCard");
        const editProfileBtn = $("#editProfileBtn");
        const cancelEdit = $("#cancelEdit");
        const form = $("form");

        // Show update profile form
        editProfileBtn.on("click", function () {
            profileCard.addClass("hidden");
            updateProfileCard.removeClass("hidden").addClass("show");
        });

        // Cancel edit and return to profile view
        cancelEdit.on("click", function () {
            updateProfileCard.addClass("hidden").removeClass("show");
            profileCard.removeClass("hidden");
        });

        // Handle profile picture preview
        $("#profilePreview").on("click", function () {
            $("#profilePicInput").click();
        });

        $("#profilePicInput").on("change", function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    $("#profilePreview").attr("src", e.target.result);
                };
                reader.readAsDataURL(file);
            }
        });

        // Handle form submission with AJAX
        form.on("submit", function (event) {
            event.preventDefault(); // Prevent default form submission

            console.log("Form submitted!"); // Debugging

            var formData = new FormData(this); // Get form data

            $.ajax({
                url: "update_profile.php", // PHP script to process the request
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json", // Expect JSON response
                success: function (data) {
                    console.log("Response received:", data); // Debugging response

                    if (data.status === "success") {
                        toastr.success(data.message); // Show success Toastr alert

                        // Delay the page reload to let the Toastr message show
                        setTimeout(function () {
                            location.reload(); // Reload the page
                        }, 1000); // 3 seconds delay
                    } else {
                        toastr.error(data.message); // Show error Toastr alert
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX error:", error);
                    toastr.error("An error occurred while updating your profile.");
                }
            });
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>