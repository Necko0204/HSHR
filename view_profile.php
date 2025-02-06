<?php
session_name('admin_session');
session_start();

// Debug: Check if session is properly set
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}
// Assuming you have a function to get user data from the database
function getUserData($admin_id) {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'humanresource');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("SELECT id, name, email, position, username, password FROM admin WHERE id = ?");
    $stmt->bind_param("i", $admin_id);

    // Execute and fetch
    $stmt->execute();
    $stmt->bind_result($id, $name, $email, $position, $username, $password);
    $stmt->fetch();

    // Close connections
    $stmt->close();
    $conn->close();

    return compact('id', 'name', 'email', 'position', 'username', 'password');
}

// Fetch user data
$userData = getUserData($_SESSION['admin_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile View</title>
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

        /* Wrapper for main content */
        .wrapper {
            margin-left: 270px;
            padding: 20px;
            max-width: calc(100% - 270px);
        }

        /* Profile Card */
        .profile-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 30px;
            max-width: 400px;
            margin: 50px auto;
        }

        .profile-card img {
            border-radius: 50%;
            width: 130px;
            height: 130px;
            border: 4px solid #6a11cb;
        }

        .profile-card h2 {
            color: #333;
            font-weight: 600;
            margin-top: 15px;
        }

        .profile-card p {
            color: #777;
            font-size: 14px;
        }

        .btn-update {
            background: #6a11cb;
            color: white;
            font-size: 16px;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            text-transform: uppercase;
            margin-top: 15px;
            transition: 0.3s;
        }

        .btn-update:hover {
            background: #2ebf91;
            transform: scale(1.05);
        }

        /* Responsive Fixes */
        @media (max-width: 768px) {
            .wrapper {
                margin-left: 0;
                max-width: 100%;
            }
        }
          /* Color and style variables */
            :root {
            --primary-color: #4a90e2;
            --accent-color: #e94e77;
            --light-bg: #f7f9fc;
            --card-bg: #ffffff;
            --border-color: #e0e0e0;
            --text-color: #333;
            --transition-speed: 0.5s;
            --border-radius: 12px;
            }

            /* Utility classes */
            .hidden {
            display: none;
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

            /* Card Styling */
            #updateProfileCard {
            max-width: 450px;
            margin: 2rem auto;
            padding: 2rem;
            background: linear-gradient(135deg, var(--light-bg), var(--card-bg));
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: var(--text-color);
            }

            #updateProfileCard h3 {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-weight: 500;
            }

            /* Form Elements */
            #updateProfileCard .form-group {
            margin-bottom: 1rem;
            }
            #updateProfileCard .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            }
            #updateProfileCard .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            font-size: 1rem;
            }
            #updateProfileCard .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 5px rgba(74, 144, 226, 0.3);
            outline: none;
            }

            /* Buttons */
            #updateProfileCard .btn {
            padding: 0.65rem 1.2rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-right: 0.5rem;
            }
            #updateProfileCard .btn-success {
            background-color: var(--primary-color);
            color: #fff;
            }
            #updateProfileCard .btn-success:hover {
            background-color: #4078c0;
            }
            #updateProfileCard .btn-secondary {
            background-color: #ccc;
            color: #333;
            }
            #updateProfileCard .btn-secondary:hover {
            background-color: #b3b3b3;
            }
            </style>
        </head>
        <body>

    <!-- Sidebar & Navbar -->
    <?php include 'sidebar.php'; ?>
    <?php include 'nav_header.php'; ?>

    <main class="wrapper">
    <div class="profile-card text-center p-4 shadow rounded" style="width: 100%; max-width: 600px; margin: 100px auto;">
        <img src="profile_picture.jpg" alt="Profile Picture" class="rounded-circle" width="120">
        <h2><?php echo htmlspecialchars($userData['name']); ?></h2>
        <p><?php echo htmlspecialchars($userData['position']); ?></p>

        <button class="btn btn-primary mt-3" id="editProfileBtn">Update Profile</button>
    </div>

    <div id="updateProfileCard" class="hidden fade-in">
    <h3>Edit Profile</h3>
    <form id="updateProfileForm" method="POST" action="update_profile.php">
      <input type="hidden" name="id" value="<?php echo $userData['id']; ?>">

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
        <input type="text" class="form-control" name="position" value="<?php echo htmlspecialchars($userData['position']); ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">Username</label>
        <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($userData['username']); ?>" required>
      </div>

      <div style="text-align: right; margin-top: 1.5rem;">
        <button type="submit" class="btn btn-success">Save Changes</button>
        <button type="button" class="btn btn-secondary" id="cancelEdit">Cancel</button>
      </div>
    </form>
  </div>

</main>

<script src="background.js"></script>
<script>
document.getElementById('editProfileBtn').addEventListener('click', function() {
    let updateCard = document.getElementById('updateProfileCard');
    updateCard.classList.remove('hidden');
    setTimeout(() => updateCard.classList.add('show'), 10);
});

document.getElementById('cancelEdit').addEventListener('click', function() {
    let updateCard = document.getElementById('updateProfileCard');
    updateCard.classList.remove('show');
    setTimeout(() => updateCard.classList.add('hidden'), 300);
});
</script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
