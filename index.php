<?php
session_name('admin_session');
session_start();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Default Fullscreen Layout */
        body {
            font-family: 'Poppins', 'Arial', sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgb(0, 0, 0), rgb(0, 0, 0));
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
            overflow: hidden;
            color: white;
            position: relative;
        }

        /* Subtle Overlay for Better Contrast */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(37, 37, 37, 0.3);
            z-index: -1;
        }

        /* Animated Background Effect */
        @keyframes backgroundAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }



        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        canvas {
            position: fixed;
            width: 100%;
            height: 100%;
        }

        a {
            position: absolute;
            bottom: 2vmin;
            right: 2vmin;
            color: rgba(255,255,255,0.2);
            text-decoration: none;
        }

        a:hover {
        color: #fff;
        }

        body::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle, #0a0a0a, #000000); 
            /* background: linear-gradient(135deg, #000000da, #DC143C); */
            z-index: -2;
            animation: backgroundAnimation 15s infinite alternate ease-in-out;
        }
        .login-card h2{
            font-weight: bold;
        }

        /* BLACK TEXT */
        .login-card h2,
        .login-card hr,
        .login-card label,
        .login-card a,
        .form-label,
        .form-check-label {
            color: black;
        }

        /* Login card positioned to the right and fits the whole screen height */
        .login-card {
            background: white;
            backdrop-filter: blur(20px);
            border-radius: 2px 0 0 2px; /* Rounded corners on the left side */
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
            width: 175px;
            height: 175px;
            border-radius: 50%;
        }

        /* Input Fields */
        .form-control {
            background: rgba(255, 255, 255, 0.1);
            color: black; /* Input text */
            border-radius: 8px;
            padding: 15px;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        /* Hover & Focus - Unified Styles */
        .form-control:hover,
        .form-control:focus {
            border-color: rgb(100, 100, 100); /* Subtle dark gray border */
            background: rgba(255, 255, 255, 0.15); /* Slightly lighter background */
            box-shadow: 0 0 5px rgba(100, 100, 100, 0.3); /* Soft glow */
            outline: none;
        }


        /* Submit Button - Now Matches Google Sign-in Button */
        .btn-primary {
            background-color: rgb(255, 255, 255); /* White background */
            border: 1px solid #ddd; /* Same border as Google button */
            color: #000; /* Black text for contrast */
            padding: 15px;
            font-size: 1.1em;
            text-transform: uppercase;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
            width: 100%;
            font-weight: bold;
        }

        .btn-primary:hover {
            color: black;
            background-color: #f1f1f1;
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

        .forgot-password {
            display: block;
            margin-top: 10px;
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }
         /* Google Sign-in Button */
        .google-signin-btn {
            background-color: white;
            border: 1px solid #ddd;
            padding: 12px;
            font-size: 1em;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
            font-weight: bold;
        }

        .google-signin-btn:hover {
            background-color: #f1f1f1;
            transform: scale(1.05);
        }

        .google-logo {
            width: 20px;
            height: 20px;
        }
        /* Center the spinner and checkmark in the button */
        .spinner-border, .check-icon {
            margin-right: 8px;
        }

        /* Checkmark styling */
        .check-icon {
            font-size: 1.2em;
            color: white;
        }

.forgot-password {
    display: block;
    margin-top: 10px;
    font-size: 14px;
    color: #007bff;
    text-decoration: none;
}

.forgot-password:hover {
    text-decoration: underline;
}

/* Google Sign-in Button */
.google-signin-btn {
    background-color: white;
    border: 1px solid #ddd;
    padding: 12px;
    font-size: 1em;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    border-radius: 8px;
    transition: all 0.3s ease-in-out;
    font-weight: bold;
}

.google-signin-btn:hover {
    background-color: #f1f1f1;
    transform: scale(1.05);
}

.google-logo {
    width: 20px;
    height: 20px;
}

/* Center the spinner and checkmark in the button */
.spinner-border, .check-icon {
    margin-right: 8px;
}

/* Checkmark styling */
.check-icon {
    font-size: 1.2em;
    color: white;
}


/* Responsive Design for 1366px and Smaller Screens */
@media (max-width: 1366px) {
    body {
        font-family: 'Poppins', 'Arial', sans-serif;
        margin: 0;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: flex-end; /* Aligns everything to the right */
        background: linear-gradient(135deg, #2c0202, #000);
        background-attachment: fixed;
        background-size: cover;
        background-position: center;
        overflow: hidden;
        position: relative;
        padding-right: 5%; /* Adjust spacing from the right */
        display: flex;
        align-items: center;
        justify-content: flex-end;
        width: 100vw; /* Ensures full viewport width */
        height: 100vh; /* Ensures full viewport height */
        padding: 0; /* Remove padding */
        margin: 0;
    }
    .login-card {
        background: white;
        border-radius: 8px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        width: 30%;
        max-width: 400px;
        text-align: center;
        overflow-y: auto;
        position: absolute;
        overflow: hidden;
    }

    .login-card h2 {
        font-size: 18px;
        margin-bottom: 15px;
    }

    .logo img {
        width: 80px;
        height: 80px;
    }

    .form-control {
        border-radius: 6px;
        padding: 8px;
        font-size: 0.85em;
    }

    .btn-primary,
    .google-signin-btn {
        padding: 12px;
        font-size: 1em;
        border-radius: 6px;
    }

    .google-logo {
        width: 20px;
        height: 20px;
    }

    .forgot-password {
        font-size: 12px;
    }
}


@media (max-width: 1280px) {
    .login-card {
        width: 50%;
        padding: 25px;
    }
}

@media (max-width: 1024px) {
    .login-card {
        width: 60%;
    }
}

@media (max-width: 768px) {
    body {
        justify-content: center;
        align-items: center;
    }

    .login-card {
        width: 90%;
        height: auto;
        padding: 20px;
    }

    .form-control {
        font-size: 0.9em;
        padding: 12px;
    }

    .btn-primary {
        font-size: 1em;
        padding: 12px;
    }
}

@media (max-width: 480px) {
    .login-card {
        width: 95%;
        padding: 15px;
    }

    .login-card h1 {
        font-size: 1.8rem;
    }
}

      /* Responsive Design for Mobile Devices */
@media (max-width: 768px) {
    /* Center the login card on mobile */
    body {
        justify-content: center;
        align-items: center;
    }

    .login-card {
        width: 90%;
        height: auto;
        padding: 20px;
        margin-left: 0;
        border-radius: 10px;
    }

    .login-card h1 {
        font-size: 2em;
    }

    .form-control {
        font-size: 0.9em;
        padding: 12px;
    }

    .btn-primary {
        font-size: 1em;
        padding: 12px;
    }
}

/* Responsive Design for 1366px Monitor Screens */
@media (min-width: 1024px) and (max-width: 1366px) {
    .login-card {
        width: 40%;
        padding: 30px;
        margin-left: auto;
        margin-right: auto;
        border-radius: 8px;
    }

    .login-card h1 {
        font-size: 2.5em;
    }

    .form-control {
        font-size: 1em;
        padding: 14px;
    }

    .btn-primary {
        font-size: 1.1em;
        padding: 14px;
    }
}

/* #vanta-bg {
            width: 100vw;
            height: 100vh;
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
        } */

</style>

<body>
<!-- <canvas></canvas> -->
<!-- <div class="box-container"></div> -->
<!-- <div id="vanta-bg"></div> -->
<div id="particles-js"></div>
    <div class="login-card">
        <div class="logo">
            <img src="images/asdasdasd123123123123123.jpg" alt="School Logo">
        </div>
        <h2>Holy Spirit Human Resource</h2>
        <hr>
        <form id="loginForm">
            <!-- Honeypot Field for Spam Protection -->
            <div style="display: none;">
                <label for="honeypot">Leave this field empty</label>
                <input type="text" id="honeypot" name="honeypot">
            </div>
            
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                        <i class="fa fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
                    <label class="form-check-label" for="rememberMe">Remember Me</label>
                </div>
                <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
            </div>
            <button type="submit" class="btn btn-primary" id="loginButton">
                <div class="spinner-border spinner-border-sm d-none" id="loginSpinner" role="status"></div>
                <span id="loginCheck" class="d-none">&#10004;</span>
                <span id="loginError" class="cross-icon d-none">&#10006;</span> <!-- Cross icon for error -->
                <span id="loginText">Login</span>
            </button>

            <hr>
            <button class="btn google-signin-btn">
                <img src="images/google-logo-9825.png" alt="Google logo" class="google-logo">
                Sign in with Google
            </button>
        </form>
    </div>
    <!-- Toastr and jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanta/0.5.24/vanta.birds.min.js"></script>
    <script>
        VANTA.BIRDS({
            el: "#vanta-bg",
            mouseControls: true,
            touchControls: true,
            gyroControls: false,
            minHeight: 200.00,
            minWidth: 200.00,
            scale: 1.00,
            scaleMobile: 1.00,
            backgroundColor: 0x600d1e, // Darker Crimson Background
            backgroundAlpha: 1.0, // Transparent
            color1: 0xdc143c, // Crimson Red Birds
            color2: 0x143cf6,  // Blue Birds
            colorMode: "varianceGradient",
            quantity: 5,
            birdSize: 1,
            wingSpan: 30,
            speedLimit: 5,
            separation: 20,
            alignment: 20,
            cohesion: 20
        });
    </script> -->

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            var passwordInput = document.getElementById('password');
            var toggleIcon = document.getElementById('toggleIcon');
            
            // Toggle the input type between password and text
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        });
    </script>
    <!-- <script>
    document.getElementById("loginForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent actual form submission

        let loginButton = document.getElementById("loginButton");
        let loginSpinner = document.getElementById("loginSpinner");
        let loginCheck = document.getElementById("loginCheck");
        let loginText = document.getElementById("loginText");

        // Show spinner and disable button
        loginSpinner.classList.remove("d-none");
        loginText.textContent = "Logging in...";
        loginButton.disabled = true;

        // Simulate login process (replace with actual backend validation)
        setTimeout(() => {
            // Hide spinner
            loginSpinner.classList.add("d-none");

            // Show checkmark
            loginCheck.classList.remove("d-none");
            loginText.textContent = "Success";

            // Change button to green
            loginButton.style.backgroundColor = "green";
            loginButton.style.border = "none";

            // Add padding to ensure checkmark has space to show up
            loginButton.style.paddingRight = "30px"; // Adjust space for checkmark

            // Ensure the checkmark is visible by triggering a reflow
            loginCheck.offsetHeight; // Trigger reflow

            // Redirect after success
            setTimeout(() => {
                window.location.href = "dashboard.php"; // Change this to your actual page
            }, 1500);
        }, 2000);
    });
</script> -->
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
    event.preventDefault(); // Prevent actual form submission

    let loginButton = document.getElementById("loginButton");
    let loginSpinner = document.getElementById("loginSpinner");
    let loginCheck = document.getElementById("loginCheck");
    let loginError = document.getElementById("loginError");
    let loginText = document.getElementById("loginText");

    // Show spinner and disable button
    loginSpinner.classList.remove("d-none");
    loginText.textContent = "Logging in...";
    loginButton.disabled = true;

    let username = document.getElementById("username").value;
    let password = document.getElementById("password").value;

    if (username === "" || password === "") {
        toastr.error("Please fill in all fields!", "Error");
        loginSpinner.classList.add("d-none");
        loginButton.disabled = false;
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

        // Hide spinner
        loginSpinner.classList.add("d-none");

        if (data.success) {
            toastr.success("Login successful! Redirecting...", "Success");

            // Show checkmark
            loginCheck.classList.remove("d-none");
            loginText.textContent = "Success";

            // Change button to green
            loginButton.style.backgroundColor = "green";
            loginButton.style.border = "none";

            // Add padding to ensure checkmark has space to show up
            loginButton.style.paddingRight = "30px"; // Adjust space for checkmark

            // Ensure the checkmark is visible by triggering a reflow
            loginCheck.offsetHeight; // Trigger reflow

            // Redirect after success
            setTimeout(() => {
                window.location.href = "dashboard.php";
            }, 1000);
        } else {
            toastr.error(data.message, "Error");

            // Show error icon
            loginError.classList.remove("d-none");
            loginText.textContent = "Failed";

            // Change button to red
            loginButton.style.backgroundColor = "red";
            loginButton.style.border = "none";

            // Add padding to ensure error icon has space to show up
            loginButton.style.paddingRight = "30px"; // Adjust space for error icon

            // Ensure the error icon is visible by triggering a reflow
            loginError.offsetHeight; // Trigger reflow

            // Re-enable button after showing error
            setTimeout(() => {
                loginButton.disabled = false;
                loginError.classList.add("d-none");
                loginText.textContent = "Login";
                loginButton.style.backgroundColor = ""; // Reset button color
                loginButton.style.paddingRight = ""; // Reset padding
            }, 1500);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        toastr.error("Something went wrong!", "Error");
        loginSpinner.classList.add("d-none");
        loginButton.disabled = false;
    });
});
        </script>
        
        <script>
  particlesJS("particles-js", {
    "particles": {
        "number": {
            "value": 300, // Balanced density to avoid overcrowding
            "density": {
                "enable": true,
                "value_area": 1000
            }
        },
        "color": {
            "value": ["#00ffff", "#8a2be2", "#ffbf00", "#ffffff", "#ff007f", "#1e90ff"] // Cool tones + gold and white for contrast
        },
        "shape": {
            "type": "circle",
            "stroke": {
                "width": 1,
                "color": "#ffffff" // Subtle glow effect
            }
        },
        "opacity": {
            "value": 0.7,
            "random": true,
            "anim": {
                "enable": true,
                "speed": 1,
                "opacity_min": 0.3,
                "sync": false
            }
        },
        "size": {
            "value": 4,
            "random": true,
            "anim": {
                "enable": true,
                "speed": 2,
                "size_min": 0.8,
                "sync": false
            }
        },
        "line_linked": {
            "enable": true,
            "distance": 150,
            "color": "#ffbf00", // Gold for contrast against red
            "opacity": 0.6,
            "width": 1
        },
        "move": {
            "enable": true,
            "speed": 1.2, // Slow and smooth for elegance
            "direction": "none",
            "random": true,
            "straight": false,
            "out_mode": "out",
            "bounce": false,
            "attract": {
                "enable": false,
                "rotateX": 600,
                "rotateY": 1200
            }
        }
    },
    "interactivity": {
        "detect_on": "canvas",
        "events": {
            "onhover": {
                "enable": true,
                "mode": "bubble"
            },
            "onclick": {
                "enable": true,
                "mode": "explode"
            },
            "resize": true
        },
        "modes": {
            "grab": {
                "distance": 200,
                "line_linked": {
                    "opacity": 1
                }
            },
            "bubble": {
                "distance": 180,
                "size": 8,
                "duration": 1.5,
                "opacity": 0.8,
                "speed": 2
            },
            "repulse": {
                "distance": 160,
                "duration": 0.4
            },
            "push": {
                "particles_nb": 8
            },
            "explode": {
                "particles_nb": 18, // Slightly more particles for a noticeable pop
                "distance": 250,
                "duration": 0.6
            },
            "remove": {
                "particles_nb": 3
            }
        }
    },
    "retina_detect": true
});


</script>


    <!-- <script>const STAR_COLOR = '#fff';
const STAR_SIZE = 3;
const STAR_MIN_SCALE = 0.2;
const OVERFLOW_THRESHOLD = 50;
const STAR_COUNT = ( window.innerWidth + window.innerHeight ) / 8;

const canvas = document.querySelector( 'canvas' ),
      context = canvas.getContext( '2d' );

let scale = 1, // device pixel ratio
    width,
    height;

let stars = [];

let pointerX,
    pointerY;

let velocity = { x: 0, y: 0, tx: 0, ty: 0, z: 0.0005 };

let touchInput = false;

generate();
resize();
step();

window.onresize = resize;
canvas.onmousemove = onMouseMove;
canvas.ontouchmove = onTouchMove;
canvas.ontouchend = onMouseLeave;
document.onmouseleave = onMouseLeave;

function generate() {

   for( let i = 0; i < STAR_COUNT; i++ ) {
    stars.push({
      x: 0,
      y: 0,
      z: STAR_MIN_SCALE + Math.random() * ( 1 - STAR_MIN_SCALE )
    });
   }

}

function placeStar( star ) {

  star.x = Math.random() * width;
  star.y = Math.random() * height;

}

function recycleStar( star ) {

  let direction = 'z';

  let vx = Math.abs( velocity.x ),
	    vy = Math.abs( velocity.y );

  if( vx > 1 || vy > 1 ) {
    let axis;

    if( vx > vy ) {
      axis = Math.random() < vx / ( vx + vy ) ? 'h' : 'v';
    }
    else {
      axis = Math.random() < vy / ( vx + vy ) ? 'v' : 'h';
    }

    if( axis === 'h' ) {
      direction = velocity.x > 0 ? 'l' : 'r';
    }
    else {
      direction = velocity.y > 0 ? 't' : 'b';
    }
  }
  
  star.z = STAR_MIN_SCALE + Math.random() * ( 1 - STAR_MIN_SCALE );

  if( direction === 'z' ) {
    star.z = 0.1;
    star.x = Math.random() * width;
    star.y = Math.random() * height;
  }
  else if( direction === 'l' ) {
    star.x = -OVERFLOW_THRESHOLD;
    star.y = height * Math.random();
  }
  else if( direction === 'r' ) {
    star.x = width + OVERFLOW_THRESHOLD;
    star.y = height * Math.random();
  }
  else if( direction === 't' ) {
    star.x = width * Math.random();
    star.y = -OVERFLOW_THRESHOLD;
  }
  else if( direction === 'b' ) {
    star.x = width * Math.random();
    star.y = height + OVERFLOW_THRESHOLD;
  }

}

function resize() {

  scale = window.devicePixelRatio || 1;

  width = window.innerWidth * scale;
  height = window.innerHeight * scale;

  canvas.width = width;
  canvas.height = height;

  stars.forEach( placeStar );

}

function step() {

  context.clearRect( 0, 0, width, height );

  update();
  render();

  requestAnimationFrame( step );

}

function update() {

  velocity.tx *= 0.96;
  velocity.ty *= 0.96;

  velocity.x += ( velocity.tx - velocity.x ) * 0.8;
  velocity.y += ( velocity.ty - velocity.y ) * 0.8;

  stars.forEach( ( star ) => {

    star.x += velocity.x * star.z;
    star.y += velocity.y * star.z;

    star.x += ( star.x - width/2 ) * velocity.z * star.z;
    star.y += ( star.y - height/2 ) * velocity.z * star.z;
    star.z += velocity.z;
  
    // recycle when out of bounds
    if( star.x < -OVERFLOW_THRESHOLD || star.x > width + OVERFLOW_THRESHOLD || star.y < -OVERFLOW_THRESHOLD || star.y > height + OVERFLOW_THRESHOLD ) {
      recycleStar( star );
    }

  } );

}

function render() {

  stars.forEach( ( star ) => {

    context.beginPath();
    context.lineCap = 'round';
    context.lineWidth = STAR_SIZE * star.z * scale;
    context.globalAlpha = 0.5 + 0.5*Math.random();
    context.strokeStyle = STAR_COLOR;

    context.beginPath();
    context.moveTo( star.x, star.y );

    var tailX = velocity.x * 2,
        tailY = velocity.y * 2;

    // stroke() wont work on an invisible line
    if( Math.abs( tailX ) < 0.1 ) tailX = 0.5;
    if( Math.abs( tailY ) < 0.1 ) tailY = 0.5;

    context.lineTo( star.x + tailX, star.y + tailY );

    context.stroke();

  } );

}

function movePointer( x, y ) {

  if( typeof pointerX === 'number' && typeof pointerY === 'number' ) {

    let ox = x - pointerX,
        oy = y - pointerY;

    velocity.tx = velocity.tx + ( ox / 8*scale ) * ( touchInput ? 1 : -1 );
    velocity.ty = velocity.ty + ( oy / 8*scale ) * ( touchInput ? 1 : -1 );

  }

  pointerX = x;
  pointerY = y;

}

function onMouseMove( event ) {

  touchInput = false;

  movePointer( event.clientX, event.clientY );

}

function onTouchMove( event ) {

  touchInput = true;

  movePointer( event.touches[0].clientX, event.touches[0].clientY, true );

  event.preventDefault();

}

function onMouseLeave() {

  pointerX = null;
  pointerY = null;

}
</script> -->
    </body> 
</html>