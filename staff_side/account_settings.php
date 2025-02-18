<?php
session_name('staff_session');
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_config.php';

if (!isset($_SESSION['employee_id'])) {
    header("Location: index.php");
    exit();
}

$employee_id = $_SESSION['employee_id'];
$stmt = $conn->prepare("
    SELECT sa.username, sbs.background_enabled, sbs.background_id, bc.background_name
    FROM staff_accounts sa
    LEFT JOIN staff_background_settings sbs ON sa.employee_id = sbs.employee_id
    LEFT JOIN background_choices bc ON sbs.background_id = bc.background_id
    WHERE sa.employee_id = ?
");

$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$staff = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../images/asdasdasd123123123123123.jpg">
    <title>Staff Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="background.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.6/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.6/dist/sweetalert2.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .profile-card {
            background: rgba(0, 0, 0, 0.6);
            border-radius: 20px;
            padding: 30px;
            margin: 50px auto;
            color: white;
        }

        .info-box {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
            color: black;
            font-weight: 600;
        }

        .info-label {
            font-size: 12px;
            color: gray;
            text-transform: uppercase;
        }

        .update-btn {
            background-color: #ff477e;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .swal2-popup {
            width: 100% !important;
            max-width: 700px !important;
            border-radius: 20px;
            padding: 30px;
        }

        .swal2-title {
            font-size: 24px;
            text-align: center;
        }

        .swal2-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto;
        }

        .swal2-content {
            font-size: 18px;
            text-align: center;
        }

        .swal2-button {
            background-color: #ff477e;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            cursor: pointer;
        }

        .swal2-button:focus {
            box-shadow: none;
        }

        .w-100 {
            width: 100%;
        }

        select.form-select {
            font-size: 14px;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        /* Style for the toggle switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;  /* Smaller width */
            height: 24px; /* Smaller height */
            margin-left: 10px;  /* Align to the right side of the label */
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px; /* Smaller size */
            width: 16px;  /* Smaller size */
            border-radius: 50%;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.4s;
        }

        /* When the checkbox is checked, slide the slider */
        input:checked + .slider {
            background-color: #4CAF50;
        }

        input:checked + .slider:before {
            transform: translateX(16px); /* Adjust the translation for the smaller switch */
        }
        .modal-label {
        color: black; /* Make the label text black */
        }

        .form-control {
            color: black; /* Make the input text black */
            background-color: #f8f9fa; /* Light background for the inputs */
            border-color: #ccc; /* Light border color */
        }

        .form-control:focus {
            color: black; /* Ensure the text remains black when focused */
            background-color: #fff; /* White background on focus */
            border-color: #007bff; /* Change border color when focused */
        }

    </style>
</head>
<body>

     <!-- Back to Dashboard Button -->
     <div style="position: absolute; top: 20px; left: 20px; z-index: 1000;">
        <a href="staff_settings.php" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Back to Settings
        </a>
    </div>
    
    <div class="main-container">
        <?php include 'staff_navbar.php'; ?>
        <div class="container mt-4">
            <div class="row">
                <!-- First Card: Username and Password Change -->
                <div class="col-md-6">
                    <div class="profile-card">
                        <h2 class="text-center"> <?php echo htmlspecialchars($staff['username']); ?> </h2>
                        <div class="text-center mt-4">
                            <!-- Update Profile Button to trigger Modal -->
                            <button class="update-btn w-100" data-bs-toggle="modal" data-bs-target="#updateProfileModal">Change Username or Password</button>
                        </div>
                    </div>
                </div>

                <!-- Second Card: Background Selection -->
                <div class="col-md-6">
                <div class="profile-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="text-center mb-0">Background Effect</h3>
                        <!-- Toggle switch next to the title -->
                        <label class="switch">
                            <input type="checkbox" id="background_toggle" <?php echo $staff['background_enabled'] == 1 ? 'checked' : ''; ?>>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="info-box mt-4">
                        <label for="background-selection" class="info-label">Choose a Background</label>
                        <select class="form-select" id="background-selection">
                            <option value="none" <?php echo ($staff['background_name'] == 'none') ? 'selected' : ''; ?>>None</option>
                            <option value="particles" <?php echo ($staff['background_name'] == 'particles') ? 'selected' : ''; ?>>Particles</option>
                            <option value="clouds" <?php echo $staff['background_name'] == 'clouds' ? 'selected' : ''; ?>>Clouds</option>
                            <option value="stars" <?php echo $staff['background_name'] == 'stars' ? 'selected' : ''; ?>>Stars</option>
                            <!-- Add more options as necessary -->
                        </select>
                    </div>

                    <!-- Optional Save Background Settings button (depending on how you want to handle background changes) -->
                    <button type="button" class="update-btn" id="saveBackgroundSettings">Save Background Settings</button>  
                </div>
            </div>


            </div>
        </div>
    </div>

    <!-- Modal for Profile Update -->
    <div class="modal fade" id="updateProfileModal" tabindex="-1" aria-labelledby="updateProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateProfileModalLabel">Change Username or Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateProfileForm">
                    <div class="mb-3">
                        <label for="username" class="form-label modal-label">New Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($staff['username']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label modal-label">New Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label modal-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitUpdateProfile">Update</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $('#submitUpdateProfile').click(function () {
            var username = $('#username').val();
            var password = $('#password').val();
            var confirm_password = $('#confirm_password').val();

            if (password !== confirm_password) {
                // SweetAlert for password mismatch
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Passwords do not match!',
                    customClass: {
                        popup: 'full-box-popup',
                        title: 'alert-title',
                        icon: 'alert-icon'
                    }
                });
                return;
            }

            $.ajax({
                url: 'update_account.php', // The file that handles the update logic
                type: 'POST',
                data: {
                    username: username,
                    password: password
                },
                success: function (response) {
                    var result = JSON.parse(response); // Parse the JSON response
                    if (result.status === 'success') {
                        // SweetAlert for success
                        Swal.fire({
                            icon: 'success',
                            title: result.message,
                            showConfirmButton: false,
                            timer: 1500,
                            customClass: {
                                popup: 'full-box-popup',
                                title: 'alert-title',
                                icon: 'alert-icon'
                            }
                        }).then(function() {
                            $('#updateProfileModal').modal('hide'); // Close the modal
                            location.reload(); // Reload the page to show updated details
                        });
                    } else {
                        // SweetAlert for error
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: result.message,
                            customClass: {
                                popup: 'full-box-popup',
                                title: 'alert-title',
                                icon: 'alert-icon'
                            }
                        });
                    }
                },
                error: function () {
                    // SweetAlert for ajax error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'There was an error updating your profile. Please try again.',
                        customClass: {
                            popup: 'full-box-popup',
                            title: 'alert-title',
                            icon: 'alert-icon'
                        }
                    });
                }
            });
        });

        $(document).ready(function () {
            // When the Save Background Settings button is clicked
            $('#saveBackgroundSettings').click(function () {
                // Get the selected background effect
                var backgroundChoice = $('#background-selection').val();
                
                // Get the state of the toggle (enabled or disabled)
                var backgroundEnabled = $('#background_toggle').prop('checked') ? 1 : 0;

                // Send the data to the server via AJAX
                $.ajax({
                    url: 'update_background.php', // This file will handle the update logic
                    type: 'POST',
                    data: {
                        background_choice: backgroundChoice,
                        background_enabled: backgroundEnabled
                    },
                    success: function (response) {
                        var result = JSON.parse(response); // Assuming response is in JSON format
                        if (result.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Background settings updated successfully!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: result.message
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'There was an error saving your settings. Please try again.'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
