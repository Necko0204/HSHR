<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            height: 100vh;
            position: relative;
            overflow: hidden;
            background: radial-gradient(circle, #141e30, #243b55); /* Dark background gradient */
        }

        /* Flying Stars */
        .flying-stars {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
        }

        .star {
            position: absolute;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            box-shadow: 0 0 8px 4px rgba(255, 255, 255, 0.7);
            animation: flyStar 8s linear infinite;
            opacity: 0.8;
        }

        /* Star Sizes and Positions */
        .star-1 { width: 12px; height: 12px; top: 10%; left: 20%; animation-duration: 7s; }
        .star-2 { width: 18px; height: 18px; top: 20%; left: 70%; animation-duration: 10s; }
        .star-3 { width: 10px; height: 10px; top: 50%; left: 40%; animation-duration: 9s; }
        .star-4 { width: 20px; height: 20px; top: 70%; left: 80%; animation-duration: 11s; }
        .star-5 { width: 14px; height: 14px; top: 85%; left: 10%; animation-duration: 8s; }
        .star-6 { width: 16px; height: 16px; top: 30%; left: 50%; animation-duration: 12s; }

        /* Flying Animation */
        @keyframes flyStar {
            0% { transform: translateY(0) translateX(0) scale(1); opacity: 0.9; }
            50% { opacity: 1; box-shadow: 0 0 15px 7px rgba(255, 255, 255, 0.6); }
            100% { transform: translateY(-100vh) translateX(30vw) scale(0.5); opacity: 0; }
        }

        /* Glassmorphism Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            color: white;
            z-index: 2;
            padding: 30px;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.3); /* Slightly opaque white background for better readability */
        }

        /* Input Field Hover Effect - Pulsing Up */
        .form-control {
            transition: all 0.3s ease-in-out;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(5px);
        }

        .form-control:hover,
        .form-control:focus {
            transform: scale(1.05); /* Pulsing up effect */
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.3);
            outline: none;
            color: white;
        }

        /* Alert Message Style */
        .alert {
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            width: 75%;
        }

        /* Fix for center positioning of the registration form */
        .registration-container {
            position: relative;
            z-index: 2; /* Ensure the form appears above the stars */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
    </style>
</head>
<body>

    <!-- Flying Stars -->
    <div class="flying-stars">
        <div class="star star-1"></div>
        <div class="star star-2"></div>
        <div class="star star-3"></div>
        <div class="star star-4"></div>
        <div class="star star-5"></div>
        <div class="star star-6"></div>
    </div>

    <!-- Alert Message -->
    <div id="alertMessage" class="alert alert-success" role="alert" style="display: none;">
        Registration successful!
    </div>

    <!-- Registration Form -->
    <div class="registration-container">
        <div class="glass-card">
            <h1 class="text-center mb-4">User Registration</h1>
            <form id="registrationForm">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
        </div>
    </div>

    <script>
        // Show alert for 3 seconds after form submission
        const alertMessage = document.getElementById('alertMessage');
        const registrationForm = document.getElementById('registrationForm');

        registrationForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent actual form submission
            alertMessage.style.display = 'block'; // Show the alert message

            // Automatically hide the alert after 3 seconds
            setTimeout(() => {
                alertMessage.style.display = 'none';
            }, 3000);
        });
    </script>

</body>
</html>
