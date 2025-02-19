<?php
session_name('staff_session');
session_start();

include 'staff_helper.php';

if (!isset($_SESSION['employee_id'])) {
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
    <style>
:root {
    --primary-color: #007bff;
    --secondary-color: #6c757d;
    --light-bg: #f8f9fa;
    --card-bg: #ffffff;
    --border-color: #ccc;
    --text-color: #333;
    --transition-speed: 0.3s;
    --border-radius: 10px;
}


/* Profile Card */
.profile-card, #updateProfileCard {
    max-width: 800px;
    margin: 50px auto;
    padding: 2.5rem;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    text-align: center;
    transition: transform 0.4s ease-in-out;
}

.profile-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 50%;
    width: 180%;
    height: 150%;
    background: linear-gradient(120deg, rgba(0, 123, 255, 0.1), rgba(255, 51, 102, 0.1));
    clip-path: ellipse(40% 60% at 50% 50%);
    animation: floatingWaves 6s infinite ease-in-out;
    transform: translateX(-50%);
    pointer-events: none;
}

@keyframes floatingWaves {
    0%, 100% { transform: translateX(-50%) translateY(-10px); opacity: 0.2; }
    50% { transform: translateX(-50%) translateY(10px); opacity: 0.3; }
}

/* Profile Image */
.profile-image-container, .profile-picture {
    width: 150px;
    height: 150px;
    margin: 0 auto;
    border-radius: 50%;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    border: 3px solid var(--border-color);
}

.profile-card img, .profile-picture2 img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Typography */
.profile-card h2 {
    color: var(--text-color);
    font-weight: 700;
    margin-top: 15px;
    font-size: 28px;
    letter-spacing: 1px;
}

.profile-card p {
    font-size: 16px;
    margin: 10px 0;
}


.profile-detail {
    background: var(--light-bg);
    padding: 12px 15px;
    border-radius: 8px;
    font-size: 16px;
    text-align: left;
}

.profile-detail strong {
    font-size: 12px;
    color: #555;
    text-transform: uppercase;
    display: block;
    margin-bottom: 5px;
}

.profile-detail span, .profile-detail p {
    font-size: 18px;
    font-weight: 500;
    color: var(--text-color);
}

/* Form */
.form-container {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    font-size: 1rem;
    font-weight: bold;
    color: #555;
    margin-bottom: 0.5rem;
}

.form-control {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid var(--border-color);
    background: #fff;
    border-radius: var(--border-radius);
    font-size: 1rem;
    transition: border var(--transition-speed);
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    outline: none;
}

/* Buttons */
.btn2, .btn-success, .btn-secondary {
    padding: 12px 25px;
    border-radius: 30px;
    font-size: 16px;
    cursor: pointer;
    text-transform: uppercase;
    font-weight: bold;
    border: none;
    transition: transform var(--transition-speed), background-color var(--transition-speed);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.btn2 {
    background: #ff3366;
    color: white;
}

.btn2:hover {
    background: #ff66a1;
    transform: scale(1.05);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
}

.btn-success {
    background-color: var(--primary-color);
    color: #fff;
}

.btn-success:hover {
    background-color: #0056b3;
}

.btn-secondary {
    background-color: var(--secondary-color);
    color: #fff;
}

.btn-secondary:hover {
    background-color: #545b62;
}

.btn:active {
    transform: scale(0.98);
}

/* Animations */
.fade-in {
    opacity: 0;
    transform: translateY(-10px);
    transition: opacity var(--transition-speed), transform var(--transition-speed);
}

.fade-in.show {
    opacity: 1;
    transform: translateY(0);
}

.hidden {
    display: none;
}

/* Mobile Adjustments */
@media (max-width: 768px) {
    .wrapper {
        margin-left: 0;
        max-width: 100%;
    }

    .form-container {
        grid-template-columns: 1fr;
    }

    .btn-container {
        flex-direction: column;
        gap: 1rem;
    }
}
    </style>
</head>
<body>


<?php include 'staff_navbar.php'; ?>

<main class="wrapper">
    <div class="profile-card text-center p-4 shadow-lg rounded" id="profileCard">
        <div class="profile-header">
            <div class="profile-image-container">
                <img src="<?php echo isset($staffData['profile_picture']) ? $staffData['profile_picture'] : '/HSHR/images/default-profile.jpg'; ?>" 
                     alt="Profile Picture" 
                     class="profile-picture2">
            </div>
            <h3><?php echo isset($staffData['firstname']) ? $staffData['firstname'] . ' ' . $staffData['lastname'] : 'Unknown'; ?> </h3>
        </div>

        <div class="profile-details-container">
            <div class="profile-detail">
                <strong>Username</strong>
                <span><?php echo isset($staffData['username']) ? $staffData['username'] : 'N/A'; ?></span>
            </div>
        </div>

        <button class="btn2 update-profile-btn" id="editProfileBtn">Update Profile</button>
    </div>

    <div id="updateProfileCard" class="hidden fade-in">
        <h3>Edit Profile</h3>
        <form action="update_profile.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $_SESSION['employee_id']; ?>">

            <!-- Profile Picture -->
            <div class="profile-picture-container">
                <div class="profile-picture">
                    <img id="profilePreview" src="<?php echo isset($staffData['profile_picture']) ? $staffData['profile_picture'] : '/HSHR/images/default-profile.jpg'; ?>" alt="Profile Picture">
                    <input type="file" id="profilePicInput" name="profile_picture" accept="image/*" style="display: none;">
                </div>
                <label class="form-label">Profile Picture</label>
            </div>

            <!-- Form Fields -->
            <div class="form-container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" name="firstname" value="<?php echo isset($staffData['first_name']) ? $staffData['first_name'] : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="lastname" value="<?php echo isset($staffData['last_name']) ? $staffData['last_name'] : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="<?php echo isset($staffData['email']) ? $staffData['email'] : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Position</label>
                    <input type="text" class="form-control" name="position" value="<?php echo isset($staffData['position']) ? $staffData['position'] : 'Unknown'; ?>" required readonly>
                </div>

                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" value="<?php echo isset($staffData['username']) ? $staffData['username'] : ''; ?>" required>
                </div>
            </div>

            <!-- Buttons -->
            <div class="btn-container2">
                <button type="submit" class="btn btn-success">Save Changes</button>
                <button type="button" class="btn btn-secondary" id="cancelEdit">Cancel</button>
            </div>
        </form>
    </div>
</main>

<script src="background.js"></script>
<script>
 document.addEventListener("DOMContentLoaded", function () {
            const profileCard = document.getElementById("profileCard");
            const updateProfileCard = document.getElementById("updateProfileCard");
            const editProfileBtn = document.getElementById("editProfileBtn");
            const cancelEdit = document.getElementById("cancelEdit");

            editProfileBtn.addEventListener("click", function () {
                profileCard.classList.add("hidden");
                updateProfileCard.classList.remove("hidden");
                updateProfileCard.classList.add("show");
            });

            cancelEdit.addEventListener("click", function () {
                updateProfileCard.classList.add("hidden");
                updateProfileCard.classList.remove("show");
                profileCard.classList.remove("hidden");
            });
        });

        document.getElementById("profilePreview").addEventListener("click", function() {
            document.getElementById("profilePicInput").click();
        });

        document.getElementById("profilePicInput").addEventListener("change", function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById("profilePreview").src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>