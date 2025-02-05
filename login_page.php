<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRMS - School Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.0/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        /* Global Styles */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #8B0000, #B22222);
            background-size: cover;
            background-position: center;
            position: relative;
        }
        /* Parallax Background Effect */
        body::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('https://source.unsplash.com/1920x1080/?school,education');
            background-size: cover;
            background-position: center;
            z-index: -1;
            filter: blur(15px);
            animation: backgroundMovement 10s infinite linear;
        }

        @keyframes backgroundMovement {
            0% { transform: translateY(0); }
            100% { transform: translateY(40px); }
        }

        /* Floating Animation for Login Card */
        @keyframes floatCard {
            0% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0); }
        }

        .login-card {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            max-width: 380px;
            width: 100%;
            z-index: 2;
            animation: floatCard 3s ease-in-out infinite;
            text-align: center;
        }

        .login-card h1 {
            font-size: 2.5em;
            color: #fff;
            margin-bottom: 30px;
            font-weight: 500;
        }

        /* School Logo and Title */
        .logo {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .logo img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }

        /* Input Fields */
        .form-control {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            border-radius: 8px;
            padding: 15px;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #34a4a0;
            box-shadow: 0 0 10px rgba(52, 164, 160, 0.5);
            outline: none;
        }

        /* Submit Button */
        .btn-primary {
            background-color: #8B0000;
            border: none;
            padding: 15px;
            font-size: 1.1em;
            text-transform: uppercase;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #2a7a75;
            transform: scale(1.05);
        }

        /* Links Styling */
        .forgot-password, .signup-link {
            text-align: center;
            color: #fff;
            font-size: 14px;
            display: block;
            margin-top: 15px;
            text-decoration: none;
        }

        .forgot-password:hover, .signup-link:hover {
            text-decoration: underline;
            color: #34a4a0;
        }

        /* Alert Styling */
        .alert {
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            width: 75%;
            margin-top: 20px;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background-color: rgba(52, 164, 160, 0.8);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-track {
            background-color: rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
<div class="login-card">
        <!-- School Logo -->
        <div class="logo">
            <img src="https://via.placeholder.com/80" alt="School Logo">
        </div>
        <h1>HRMS Login</h1>
        <form id="loginForm" action="login_logic.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.0/dist/sweetalert2.all.min.js"></script>

    <script>
        // Check if PHP session contains login status
        <?php if (isset($_SESSION['login_status'])): ?>
            // Handle SweetAlert based on login status
            const loginStatus = '<?php echo $_SESSION['login_status']; ?>';
            if (loginStatus === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Login successful!',
                    text: 'Redirecting...',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = "index.php"; // Redirect after 2 seconds
                });
            } else if (loginStatus === 'invalid_password') {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid password!',
                    text: 'Please try again.'
                });
            } else if (loginStatus === 'no_user') {
                Swal.fire({
                    icon: 'warning',
                    title: 'No account found!',
                    text: 'Please check your username.'
                });
            }
            <?php unset($_SESSION['login_status']); // Clear session message ?>
        <?php endif; ?>
    </script>

</body>
</html>