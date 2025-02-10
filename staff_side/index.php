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
            0% { box-shadow: 0 0 10px rgba(0, 123, 255, 0.2); }
            50% { box-shadow: 0 0 20px rgba(0, 123, 255, 0.5); }
            100% { box-shadow: 0 0 10px rgba(0, 123, 255, 0.2); }
        }
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            font-family: Arial, sans-serif;
        }
        .login-container {
            max-width: 400px;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            animation: fadeIn 1s ease-in-out;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: 0.3s;
            animation: pulse 1.5s infinite;
        }
        .btn-primary:hover {
            background-color: #0056b3;
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
    <div class="login-container">
        <h3 class="text-center mb-4">Login</h3>
        <form>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Enter your password" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <div class="text-center mt-3">
            <a href="#">Forgot password?</a>
        </div>
    </div>
</body>
</html>
