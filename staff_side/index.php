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
    <link rel="icon" type="image/png" href="../images/asdasdasd123123123123123.jpg">
    <title>Holy Spirit Human Resource</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgb(113, 13, 13);
            font-family: 'Poppins', sans-serif;
            color: white;
            margin: 0;
            overflow: hidden;
        }
        .animated-background {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        .animated-background div {
            position: absolute;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.2);
            animation: float 6s infinite ease-in-out;
            opacity: 0.6;
            border-radius: 50%;
        }
        .container {
            z-index: 10;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            display: flex;
            align-items: center;
            justify-content: center;
            max-width: 900px;
            width: 90%;
            padding: 2rem;
            position: relative;
            text-align: center;
            flex-direction: row-reverse;
        }
        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 50%;
        }
        .logo {
            width: 200px;
            height: 200px;
            background: url('../images/asdasdasd123123123123123.jpg') no-repeat center;
            background-size: cover;
            border-radius: 50%;
        }
        .login-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 50%;
        }
        .btn-primary {
            background-color: #ff6f61;
            border: none;
            padding: 0.75rem 2rem;
            font-size: 1.2rem;
            border-radius: 20px;
            transition: background-color 0.3s, transform 0.3s;
            box-shadow: 0 4px 15px rgba(255, 111, 97, 0.2);
        }
        .btn-primary:hover {
            background-color: #e35344;
            transform: translateY(-3px);
        }
        .form-control {
            background: rgba(255, 255, 255, 0.3);
            border: none;
            color: white;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        .text-center a {
            color: #ffeb3b;
            text-decoration: none;
            transition: color 0.3s;
        }
        .text-center a:hover {
            color: #ffc107;
        }
        @keyframes float {
            0% { transform: translateY(0) translateX(0); }
            50% { transform: translateY(-30px) translateX(30px); opacity: 0.3; }
            100% { transform: translateY(0) translateX(0); }
        }
        @media (max-width: 768px) {
    .container {
        flex-direction: column;
        padding: 1.5rem;
        width: 95%;
        align-items: center;
        text-align: center;
    }
    .logo-container, .login-container {
        width: 100%;
        margin-bottom: 1.5rem;
    }
    .logo-container {
        order: -1; /* moves logo to top on mobile */
    }
    .logo {
        width: 150px;
        height: 150px;
        margin: 0 auto;
    }
    .btn-primary {
        font-size: 1rem;
        padding: 0.5rem 1.5rem;
    }
}

/* Small Mobile */
@media (max-width: 480px) {
    .btn-primary {
        font-size: 0.95rem;
        padding: 0.5rem 1.2rem;
    }
    .form-control {
        font-size: 0.95rem;
    }
    .container {
        padding: 1rem;
    }
}
    </style>
</head>
<body>
    <div class="animated-background">
        <?php for ($i = 0; $i < 30; $i++): ?>
            <div style="top:<?= rand(0, 100) ?>vh; left:<?= rand(0, 100) ?>vw; animation-duration:<?= rand(6, 12) ?>s;"></div>
        <?php endfor; ?>
    </div>

    <div class="container">
        <div class="login-container">
            <h3 class="text-center mb-4 fw-bold">
                <i class="fa-solid fa-user-lock me-2"></i> Login
            </h3>
            <form id="loginForm" method="POST" action="login_logic.php" class="w-100">
                <div class="mb-3 text-start">
                    <label for="username" class="form-label">
                        <i class="fa-solid fa-user me-2"></i> Username
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Your username" required>
                    </div>
                </div>
                <div class="mb-3 text-start">
                    <label for="password" class="form-label">
                        <i class="fa-solid fa-lock me-2"></i> Password
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Your password" required>
                        <span class="input-group-text">
                            <i class="fa-solid fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                        </span>
                    </div>
                </div>
                <script>
                    document.getElementById('togglePassword').addEventListener('click', function () {
                        const passwordField = document.getElementById('password');
                        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                        passwordField.setAttribute('type', type);
                        this.classList.toggle('fa-eye-slash');
                    });
                </script>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa-solid fa-right-to-bracket me-2"></i> Login
                </button>
            </form>
            <div class="text-center mt-3">
                <a href="forgot_password.php">
                    <i class="fa-solid fa-key me-2"></i> Forgot password?
                </a>
            </div>
        </div>
        <div class="logo-container">
            <div class="logo">
                <!-- Placeholder for logo content, if any -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            $('#loginForm').submit(function (event) {
            event.preventDefault();
            let formData = $(this).serialize();
            $.ajax({
                type: "POST",
                url: "login_logic.php",
                data: formData,
                dataType: "json",
                success: function (response) {
                if (response.status === "success") {
                    Swal.fire({
                    icon: "success",
                    title: "<h3 style='color: #28a745; font-weight: bold;'>Success!</h3>",
                    html: `<p style='color: #6c757d;'>${response.message}</p>`,
                    background: "#f8f9fa",
                    confirmButtonColor: "#28a745",
                    showConfirmButton: false,
                    timer: 1500
                    }).then(() => {
                    window.location.href = response.redirect;
                    });
                } else {
                    Swal.fire({
                    icon: "error",
                    title: "<h3 style='color: #dc3545; font-weight: bold;'>Login Failed</h3>",
                    html: `<p style='color: #6c757d;'>${response.message}</p>`,
                    background: "#f8f9fa",
                    confirmButtonColor: "#dc3545"
                    });
                }
                },
                error: function () {
                Swal.fire({
                    icon: "error",
                    title: "<h3 style='color: #dc3545; font-weight: bold;'>Oops...</h3>",
                    html: "<p style='color: #6c757d;'>Something went wrong! Please try again.</p>",
                    background: "#f8f9fa",
                    confirmButtonColor: "#dc3545"
                });
                }
            });
            });
        });
    </script>
</body>
</html>