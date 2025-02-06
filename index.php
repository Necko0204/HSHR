<?php
session_name('admin_session');
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRMS - School Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
/* Fullscreen Layout */
body {
    font-family: 'Helvetica', 'Arial', sans-serif;
    margin: 0;
    height: 100vh;
    display: flex;
    align-items: center;
    background: black;
    background-size: cover;
    background-position: center;
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

/* Login card positioned to the right and fits the whole screen height */
.login-card {
    background: white;
    backdrop-filter: blur(20px);
    border-radius: 2px 0 0 20px; /* Rounded corners on the left side */
    padding: 40px;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
    width: 30%; /* Takes up 30% of the screen width */
    height: 100vh; /* Takes up the full height of the screen */
    z-index: 2;
    text-align: center;
    margin-left: auto; /* Pushes it to the right */
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.login-card h1 {
    font-size: 2.8em;
    color: #000;
    margin-bottom: 30px;
    font-weight: 600;
    letter-spacing: 1px;
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
    color: #000;
    border-radius: 8px;
    padding: 15px;
    font-size: 1em;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color:rgb(0, 129, 129);
    box-shadow: 0 0 10px rgba(255, 75, 75, 0.5);
    outline: none;
}

/* Submit Button */
.btn-primary {
    background-color:rgb(12, 94, 2);
    border: none;
    padding: 15px;
    font-size: 1.1em;
    text-transform: uppercase;
    border-radius: 8px;
    transition: all 0.3s ease-in-out;
    width: 100%;
}

.btn-primary:hover {
    background-color: #FF4B4B;
    transform: scale(1.05);
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-thumb {
    background-color: rgba(255, 75, 75, 0.8);
    border-radius: 10px;
}

::-webkit-scrollbar-track {
    background-color: rgba(0, 0, 0, 0.2);
}

/* Animated Boxes */
.box-container {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: -1;
}

.box {
    position: absolute;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #D3D3D3, #A9A9A9); /* Gray color */
    border-radius: 12px;
    animation: moveBox 12s infinite linear alternate, pulseBox 3s infinite ease-in-out;
    box-shadow: 0 0 15px rgba(169, 169, 169, 0.5);
}

@keyframes moveBox {
    0% { transform: translateY(0); }
    100% { transform: translateY(120px); }
}

@keyframes pulseBox {
    0%, 100% { transform: scale(1); opacity: 0.8; }
    50% { transform: scale(1.4); opacity: 0.3; }
}

</style>

<body>
    <div class="box-container"></div>
        <div class="login-card">
            <div class="logo">
                <img src="https://via.placeholder.com/80" alt="School Logo">
            </div>
            <h1 style="font-family: 'Poppins', sans-serif;">HRMS Login</h1>
            <form id="loginForm">
                <div class="mb-3">
                    <label for="username" class="form-label" style="font-family: 'Poppins', sans-serif;">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label" style="font-family: 'Poppins', sans-serif;">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary" style="font-family: 'Poppins', sans-serif;">Login</button>
            </form>
        </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const boxContainer = document.querySelector(".box-container");
            for (let i = 0; i < 20; i++) {
                let box = document.createElement("div");
                box.classList.add("box");
                box.style.left = `${Math.random() * 100}vw`;
                box.style.top = `${Math.random() * 100}vh`;
                box.style.animationDelay = `${Math.random() * 5}s`;
                boxContainer.appendChild(box);
            }
        });
    </script>
</body>

            <!-- Toastr and jQuery -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

            <script>
            toastr.options = {
                closeButton: true,
                progressBar: true,
                timeOut: 1000, // 1 second
                extendedTimeOut: 800, // Faster fade out after hover
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut"
            };

            document.getElementById("loginForm").addEventListener("submit", function(event) {
                event.preventDefault();

                let username = document.getElementById("username").value;
                let password = document.getElementById("password").value;

                if (username === "" || password === "") {
                    toastr.error("Please fill in all fields!", "Error");
                    return;
                }

                let formData = new FormData();
                formData.append("username", username);
                formData.append("password", password);

                fetch("login_logic.php", {
                    method: "POST",
                    body: formData,
                    credentials: "include" // Ensures session cookies persist
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Response:", data); // Debugging line

                    if (data.success) {
                        toastr.success("Login successful! Redirecting...", "Success");

                        // Debug session after login
                        setTimeout(() => {
                            window.location.href = "dashboard.php";
                        }, 1000);
                    } else {
                        toastr.error(data.message, "Error");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    toastr.error("Something went wrong!", "Error");
                });
            });
        </script>
        </body> 
    </html>
</html>