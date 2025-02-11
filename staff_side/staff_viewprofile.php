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
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f4f4;
            color: black;
            min-height: 100vh;
        }

        .wrapper {
            margin-left: 270px;
            padding: 30px;
            max-width: calc(100% - 270px);
        }

        .profile-card {
            width: 100%; /* Extend beyond its container */
            margin-left: -10%; /* Center it properly */
            background: rgba(255, 255, 255, 0.2); /* Semi-transparent white */
            backdrop-filter: blur(10px); /* Optional: Adds a blur effect for a glassy look */
            border-radius: 20px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 30px;
            max-width: 4500px;
            margin: 50px auto;
            position: relative;
            overflow: hidden;
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
            0% {
                transform: translateX(-50%) translateY(-10px);
                opacity: 0.2;
            }
            50% {
                transform: translateX(-50%) translateY(10px);
                opacity: 0.3;
            }
            100% {
                transform: translateX(-50%) translateY(-10px);
                opacity: 0.2;
            }
        }

        .profile-image-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid #fff;
        }

        .profile-card h2 {
            color: #000;
            font-weight: 700;
            margin-top: 15px;
            font-size: 28px;
            letter-spacing: 1px;
        }

        .profile-card p {
            color: #000;
            font-size: 16px;
            margin: 10px 0;
        }

        .btn {
            background: #ff3366;
            color: white;
            font-size: 16px;
            border: none;
            padding: 12px 25px;
            border-radius: 30px;
            text-transform: uppercase;
            margin-top: 20px;
            transition: 0.3s;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .btn:hover {
            background: #ff66a1;
            transform: scale(1.05);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .btn:active {
            transform: scale(0.98);
        }

        @media (max-width: 768px) {
            .wrapper {
                margin-left: 0;
                max-width: 100%;
            }
        }
        .fade-in {
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity var(--transition-speed) ease, transform var(--transition-speed) ease;
        }

        .fade-in.show {
            opacity: 1;
            transform: translateY(0);
        }

        .hidden {
            display: none;
        }
        :root {
            --primary-color: #007bff;  /* Professional Blue */
            --secondary-color: #6c757d; /* Neutral Gray */
            --light-bg: #f8f9fa;
            --card-bg: #ffffff;
            --border-color: #ccc;
            --text-color: #333;
            --transition-speed: 0.3s;
            --border-radius: 10px;
        }

        /* Profile Card Wrapper */
        #updateProfileCard {
            max-width: 800px;
            margin: 4rem auto;
            padding: 2.5rem;
            background: rgba(255, 255, 255, 0.2); /* Transparent background */
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            font-family: 'Poppins', sans-serif;
            color: black; /* Ensures text is black */
            transition: transform 0.4s ease-in-out;
        }

        /* Profile Picture Section */
        .profile-picture-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            position: relative;
            border: 3px solid var(--border-color);
        }

        .profile-picture img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Form Section */
        .form-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-size: 1rem;
            font-weight: bold;
            color: #555; /* Neutral Gray */
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid var(--border-color);
            background: #fff;
            border-radius: 6px;
            font-size: 1rem;
            color: var(--text-color);
            transition: border 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            outline: none;
        }

        /* Buttons */
        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.8rem 1.8rem;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: transform 0.3s ease, background-color 0.3s ease;
            text-transform: uppercase;
            font-weight: bold;
            border: none;
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

        /* Mobile Adjustments */
        @media (max-width: 768px) {
            .form-container {
                grid-template-columns: 1fr;
            }

            .btn-container {
                flex-direction: column;
                gap: 1rem;
            }
        }   
        .profile-details-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .profile-detail {
            background: #f8f9fa;
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

        .profile-detail span,
        .profile-detail p {
            font-size: 18px;
            font-weight: 500;
            color: #333;
        }

        .full-width {
            grid-column: span 2;
        }

        .update-profile-btn:hover {
            background: linear-gradient(135deg, #0056b3, #003d80);
            transform: scale(1.05);
        }

    </style>
</head>
<body>


<?php include 'staff_navbar.php'; ?>

<?php

// Assuming $staff_id is retrieved from session or request
$staff_id = $_SESSION['staff_id'] ?? $_GET['id'] ?? 0;

// Fetch staff data
$data = getStaffData($staff_id);

if ($data) {
    $firstname = htmlspecialchars($data['firstname']);
    $lastname = htmlspecialchars($data['lastname']);

    $username = htmlspecialchars($data['username']);

    $profile_picture = htmlspecialchars($data['profile_picture'] ?? 'default.jpg');
} else {
    echo "Staff data not found.";
    exit;
}
?>

<main class="wrapper">
    <div class="profile-card text-center p-4 shadow-lg rounded" id="profileCard">
        <div class="profile-header">
            <div class="profile-image-container">
                <img src="<?php echo $profile_picture; ?>" 
                     alt="Profile Picture" 
                     class="profile-picture">
            </div>
            <h3><?php echo "$firstname $lastname"; ?> Profile</h3>
        </div>

        <div class="profile-details-container">

            <div class="profile-detail">
                <strong>Username</strong>
                <span><?php echo $username; ?></span>
            </div>
           
        </div>

        <button class="btn update-profile-btn" id="editProfileBtn">Update Profile</button>
    </div>

    <div id="updateProfileCard" class="hidden fade-in">
        <h3>Edit Profile</h3>
        <form action="update_profile.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $staff_id; ?>">

            <!-- Profile Picture -->
            <div class="profile-picture-container">
                <div class="profile-picture">
                    <img id="profilePreview" src="<?php echo $profile_picture; ?>" alt="Profile Picture">
                    <input type="file" id="profilePicInput" name="profile_picture" accept="image/*" style="display: none;">
                </div>
                <label class="form-label">Profile Picture</label>
            </div>

            <!-- Form Fields -->
            <div class="form-container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" name="firstname" value="<?php echo $firstname; ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="lastname" value="<?php echo $lastname; ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="<?php echo $email; ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Position</label>
                    <input type="text" class="form-control" name="position" value="<?php echo $position; ?>" required readonly>
                </div>

                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" value="<?php echo $username; ?>" required>
                </div>
            </div>

            <!-- Buttons -->
            <div class="btn-container">
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