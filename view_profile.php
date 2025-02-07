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
            padding: 20px;
            max-width: calc(100% - 270px);
        }

        .profile-card {
            width: 120%; /* Extend beyond its container */
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
            top: 20%;
            left: 50%;
            width: 150%;
            height: 150%;
            background-color: rgba(0, 0, 0, 0.1);
            clip-path: polygon(10% 20%, 20% 5%, 30% 25%, 50% 0, 70% 25%, 80% 5%, 90% 20%, 100% 0, 100% 100%, 0 100%, 0 0);
            animation: hammerclawAnimation 2s infinite ease-in-out;
            transform: translateX(-50%) rotate(-45deg);
            pointer-events: none; /* Ensure it doesn't interfere with clicks */
        }

        @keyframes hammerclawAnimation {
            0% {
                transform: translateX(-50%) rotate(-45deg);
                opacity: 0.1;
            }
            50% {
                transform: translateX(-50%) rotate(0deg);
                opacity: 0.3;
            }
            100% {
                transform: translateX(-50%) rotate(45deg);
                opacity: 0.1;
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
            --primary-color: #4a90e2;  /* Blue */
            --accent-color: #e94e77;   /* Pink */
            --light-bg: #f7f9fc;
            --card-bg: #ffffff;
            --border-color: #e0e0e0;
            --text-color: #333;
            --transition-speed: 0.3s;
            --border-radius: 15px;
            --hover-scale: 1.05;
        }

        /* Profile Card Wrapper */
        #updateProfileCard {
            display: grid;
            grid-template-columns: 300px auto;
            gap: 2rem;
            max-width: 1000px;
            margin: 4rem auto;
            padding: 2.5rem;
            background: rgba(255, 255, 255, 0.2); /* Semi-transparent white */
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
            width: 180px;
            height: 180px;
            border-radius: 50%;
            overflow: hidden;
            position: relative;
            border: 5px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0px 0px 20px rgba(0, 153, 255, 0.6);
            transition: transform 0.4s ease-in-out;
        }

        .profile-picture:hover {
            transform: scale(1.1) rotate(5deg);
        }

        .profile-picture img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Form Section */
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
            color: #00d4ff;
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            font-size: 1rem;
            color: #ffffff;
            transition: border 0.3s ease;
        }

        .form-control:focus {
            border-color: #00d4ff;
            box-shadow: 0 0 10px rgba(0, 212, 255, 0.5);
            outline: none;
        }

        /* Buttons */
        .btn-container {
            grid-column: span 2;
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.8rem 1.8rem;
            border-radius: 50px;
            font-size: 1rem;
            cursor: pointer;
            transition: transform 0.3s ease, background-color 0.3s ease;
            text-transform: uppercase;
            font-weight: bold;
        }

        .btn-success {
            background-color: #00d4ff;
            color: #fff;
            border: none;
        }

        .btn-success:hover {
            background-color: #008cff;
            transform: scale(1.1);
        }

        .btn-secondary {
            background-color: #ff4d6d;
            color: #fff;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #d91e40;
            transform: scale(1.1);
        }

        /* Mobile Adjustments */
        @media (max-width: 768px) {
            #updateProfileCard {
                grid-template-columns: 1fr;
                text-align: center;
                padding: 2rem;
            }

            .profile-card {
                width: 100%;
            }

            .profile-picture-container {
                margin-bottom: 1rem;
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

<?php include 'sidebar.php'; ?>
<?php include 'nav_header.php'; ?>

<main class="wrapper">
    <div class="profile-card text-center p-4 shadow-lg rounded" style="width: 100%; max-width: 600px; margin: 100px auto;">
        <div class="profile-image-container">
            <img src="<?php echo !empty($userData['profile_picture']) ? $userData['profile_picture'] : 'uploads/profile_pictures/default.jpg'; ?>" 
            alt="Profile Picture" 
            class="rounded-circle">
        </div>
        <h2><?php echo htmlspecialchars($userData['name']); ?></h2>
        <p class="mb-1"><strong>Position:</strong> <?php echo htmlspecialchars($userData['position']); ?></p>
        <p class="mb-1"><strong>Age:</strong> <?php echo htmlspecialchars($userData['age']); ?></p>
        <p class="mb-3"><strong>Bio:</strong> <?php echo htmlspecialchars($userData['bio']); ?></p>
        <button class="btn btn-primary mt-3" id="editProfileBtn">Update Profile</button>
    </div>

<div id="updateProfileCard" class="hidden fade-in">
    <h3>Edit Profile</h3>
    <form action="update_profile.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $userData['id']; ?>">

        <!-- Profile Picture Section -->
        <div class="profile-picture-container">
            <div class="profile-picture">
                <img id="profilePreview" src="<?php echo !empty($userData['profile_picture']) ? $userData['profile_picture'] : 'uploads/profile_pictures/default.jpg'; ?>" alt="Profile Picture">
                <input type="file" id="profilePicInput" name="profile_picture" accept="image/*" style="display: none;">
            </div>
            <label class="form-label">Profile Picture</label>
        </div>


        <!-- Form Fields Section -->
        <div class="form-container">
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

            <!-- Buttons Section -->
            <div class="btn-container">
                <button type="submit" class="btn btn-success">Save Changes</button>
                <button type="button" class="btn btn-secondary" id="cancelEdit">Cancel</button>
            </div>
        </div>
    </form>
</div>

</main>
<script src="background.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const editProfileBtn = document.getElementById("editProfileBtn");
    const updateProfileCard = document.getElementById("updateProfileCard");
    const cancelEdit = document.getElementById("cancelEdit");

    if (editProfileBtn) {
        editProfileBtn.addEventListener("click", function () {
            updateProfileCard.classList.remove("hidden");
            updateProfileCard.classList.add("show");
        });
    }

    if (cancelEdit) {
        cancelEdit.addEventListener("click", function () {
            updateProfileCard.classList.add("hidden");
            updateProfileCard.classList.remove("show");
        });
    }
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