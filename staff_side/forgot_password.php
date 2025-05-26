<?php
session_name('staff_session');
session_start();
include 'db_config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #4b0000, black);
            color: white;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            max-width: 500px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        .btn-primary {
            background-color: #8b0000;
            border: none;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background-color: #660000;
        }
        .form-control {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 class="text-center">Reset Password</h3>
        <p class="text-center">Enter your email to receive password reset instructions.</p>
        <form method="POST" action="reset_password.php">
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
        </form>
        <div class="text-center mt-3">
            <a href="index.php" class="text-white">Back to Login</a>
        </div>
    </div>
</body>
</html>
