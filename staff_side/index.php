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
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 10px rgba(139, 0, 0, 0.2); }
            50% { box-shadow: 0 0 20px rgba(139, 0, 0, 0.5); }
            100% { box-shadow: 0 0 10px rgba(139, 0, 0, 0.2); }
        }
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #4b0000, black);
            color: white;
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
            position: relative;
        }
        .square {
            position: absolute;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.2);
            animation: float 5s infinite ease-in-out;
        }
        @keyframes float {
            0% { transform: translateY(0) translateX(0); opacity: 0.7; }
            50% { transform: translateY(-50px) translateX(50px); opacity: 0.3; }
            100% { transform: translateY(0) translateX(0); opacity: 0.7; }
        }
        .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1000px;
            width: 100%;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            padding: 2.5rem;
        }
        .logo-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .logo {
            width: 300px;
            height: 300px;
            background: url('../images/asdasdasd123123123123123.jpg') no-repeat center;
            background-size: cover;
            border-radius: 50%;
        }
        .login-container {
            flex: 1;
            padding: 2.5rem;
        }
        .btn-primary {
            background-color: #8b0000;
            border: none;
            transition: 0.3s;
            animation: pulse 1.5s infinite;
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
        .form-label {
            color: white;
        }
        .text-center a {
            color: #ffcc00;
            text-decoration: none;
            transition: 0.3s;
        }
        .text-center a:hover {
            color: #ff9900;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <div class="logo"></div>
        </div>
        <div class="login-container">
            <h3 class="text-center mb-4">Login</h3>
            <form method="POST" action="login_logic.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <div class="text-center mt-3">
                <a href="#">Forgot password?</a>
            </div>
        </div>
    </div>
    <script>
        for (let i = 0; i < 30; i++) {
            let square = document.createElement('div');
            square.classList.add('square');
            square.style.top = Math.random() * 100 + 'vh';
            square.style.left = Math.random() * 100 + 'vw';
            square.style.animationDuration = (Math.random() * 3 + 3) + 's';
            document.body.appendChild(square);
        }
    </script>
</body>
</html>
