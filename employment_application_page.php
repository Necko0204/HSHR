<?php
session_start();
include 'db_config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/asdasdasd123123123123123.jpg">
    <title>Employment Application</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
    body {
        background: linear-gradient(to bottom, rgb(129, 0, 28), #16213e);
        min-height: 100vh;
        position: relative;
        overflow-x: hidden;
    }

    .star {
        position: fixed;
        width: 10px;
        height: 10px;
        background: white;
        border-radius: 50%;
        animation: fall linear infinite, glow 1.5s ease-in-out infinite alternate;
        z-index: 0;
    }

    @keyframes fall {
        0% {
            transform: translateY(0) translateX(0);
            opacity: 1;
        }
        100% {
            transform: translateY(100vh) translateX(50px);
            opacity: 0;
        }
    }

    @keyframes glow {
        from {
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
        }
        to {
            box-shadow: 0 0 20px rgba(255, 255, 255, 1);
        }
    }

    .form-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        position: relative;
        z-index: 1; /* Ensure form is above the stars */
    }

    .form-label {
        font-weight: 500;
        color: #2c3e50;
    }

    .form-control {
        border: 1px solid #cbd5e0;
        padding: 0.75rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #4a90e2;
        box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
    }

    .form-control:hover {
        border-color: #4a90e2;
        box-shadow: 0 0 5px rgba(74, 144, 226, 0.5);
    }

    fieldset {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        position: relative; /* Add this */
        margin-top: 15px;   /* Add this */
    }

    legend {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        padding: 0 10px;
        margin-bottom: 0;
        width: auto;
        position: absolute; /* Add this */
        top: -15px;        /* Add this */
        
    }

    .btn-submit {
        background: linear-gradient(to right, #4a90e2, #357abd);
        border: none;
        padding: 12px 30px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(74, 144, 226, 0.4);
    }

    .logo-container {
        background: white;
        padding: 15px;
        border-radius: 50%;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }
    .logo-container img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%; 
    }

    </style>
</head>
<body>
    <!-- Stars will be inserted here by JavaScript -->
    <div class="container py-5">
        <div class="form-container p-4 p-md-5 mx-auto" style="max-width: 10000px;">
            <div class="text-center mb-4">
                <div class="logo-container d-inline-block">
                    <img src="images/landscape_logo.svg" alt="Company Logo" style="width: 150px;">
                </div>
                <h2 class="mb-4  fw-bold">Employment Application</h2>
            </div>
       
            <form action="submit_applicant_application.php" method="post" enctype="multipart/form-data">
            <fieldset>
            <legend>Personal Information</legend>
            <div class="row g-3">
                <div class="col-md-4">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastname" name="lastname" required>
                </div>
                <div class="col-md-4">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstname" name="firstname" required>
                </div>
                <div class="col-md-4">
                <label for="middlename" class="form-label">Middle Name</label>
                <input type="text" class="form-control" id="middlename" name="middlename">
                </div>
                <div class="col-md-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="col-md-4">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-select" id="gender" name="gender" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                </div>
                <div class="col-md-4">
                    <label for="dateofbirth" class="form-label">Date of Birth</label>
                    <input type="text" class="form-control" id="dateofbirth" name="dateofbirth" required>
                </div>
                <div class="col-md-4">
                <label for="contact" class="form-label">Contact Number</label>
                <input type="text" class="form-control" id="contact" name="contact" required>
                </div>
            </div>
            </fieldset>
            
            <fieldset>
            <legend>Educational Background</legend>
            <div class="row g-3">
                <div class="col-md-6">
                <label for="highest_degree" class="form-label">Highest Degree Attained</label>
                <input type="text" class="form-control" id="highest_degree" name="highest_degree" required>
                </div>
                <div class="col-md-6">
                <label for="university" class="form-label">University/College</label>
                <input type="text" class="form-control" id="university" name="university" required>
                </div>
                <div class="col-md-6">
                <label for="graduation_year" class="form-label">Year of Graduation</label>
                <input type="number" class="form-control" id="graduation_year" name="graduation_year" required>
                </div>
                <div class="col-md-6">
                <label for="major" class="form-label">Major</label>
                <input type="text" class="form-control" id="major" name="major">
                </div>
            </div>
            </fieldset>
            
            <fieldset>
            <legend>Teaching Experience</legend>
            <div class="row g-3">
                <div class="col-md-6">
                <label for="previous_school" class="form-label">Previous School</label>
                <input type="text" class="form-control" id="previous_school" name="previous_school" required>
                </div>
                <div class="col-md-6">
                <label for="years_of_experience" class="form-label">Years of Experience</label>
                <input type="number" class="form-control" id="years_of_experience" name="years_of_experience" required>
                </div>
                <div class="col-md-12">
                <label for="subjects_taught" class="form-label">Subjects Taught</label>
                <input type="text" class="form-control" id="subjects_taught" name="subjects_taught">
                </div>
            </div>
            </fieldset>
            
            <fieldset>
            <legend>License and Certification</legend>
            <div class="row g-3">
                <div class="col-md-6">
                <label for="license" class="form-label">Professional Teaching License</label>
                <input type="text" class="form-control" id="license" name="license" required>
                </div>
                <div class="col-md-6">
                <label for="certifications" class="form-label">Other Certifications</label>
                <input type="text" class="form-control" id="certifications" name="certifications">
                </div>
            </div>
            </fieldset>

            <fieldset>
            <legend>Upload CV/Resume</legend>
            <div class="row g-3">
                <div class="col-md-12">
                <label for="resume" class="form-label">CV/Resume</label>
                <input type="file" class="form-control" id="resume" name="resume" accept=".pdf,.doc,.docx" required>
                </div>
            </div>
            </fieldset>
            
            <div class="text-center">
                <button type="submit" class="btn btn-submit">Submit Application</button>
                <p class="mt-3">Please note that it will take at least 2-3 business days for the HR department to respond.</p>
            </div>
        </form>
    </div>
</body>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
                <script>
                    flatpickr("#dateofbirth", {
                        dateFormat: "Y-m-d",
                        maxDate: new Date().fp_incr(-21 * 365), // Ensure age is not lower than 21
                        onChange: function(selectedDates, dateStr, instance) {
                            const age = new Date().getFullYear() - new Date(dateStr).getFullYear();
                            if (age < 21) {
                                alert("Age must be at least 21 years.");
                                instance.clear();
                            }
                        }
                    });
                </script>
    <!-- Falling Stars Animation -->
    <script>
    function createStar() {
        const star = document.createElement('div');
        star.className = 'star';
        const startX = Math.random() * window.innerWidth; // Random starting X position
        const duration = Math.random() * 3 + 2; // Random duration between 2s to 5s

        star.style.left = startX + 'px';
        star.style.top = '-' + (Math.random() * 50) + 'px'; // Small offset to start offscreen
        star.style.animationDuration = duration + 's';
        document.body.appendChild(star);

        // Remove star after animation
        setTimeout(() => {
            star.remove();
        }, duration * 1000);
    }

    // Create stars periodically
    setInterval(createStar, 300);

    // Create initial batch of stars
    for (let i = 0; i < 20; i++) {
        setTimeout(createStar, i * 100);
    }
</script>
<script>
document.querySelector("form").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent default form submission

    let formData = new FormData(this);

    fetch("submit_applicant_application.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            Swal.fire({
                title: "🎉 Success!",
                text: data.message,
                icon: "success",
                showConfirmButton: false,
                timer: 800
            });

            // Ensure redirection happens after 0.8s
            setTimeout(() => {
                window.location.href = "application_success.php";
            }, 800);
        } else {
            Swal.fire({
                title: "❌ Error!",
                text: data.message,
                icon: "error",
                confirmButtonText: "Try Again"
            });
        }
    })
    .catch(error => {
        console.error("Error:", error);
        Swal.fire({
            title: "❌ Error!",
            text: "Something went wrong. Please try again.",
            icon: "error",
            confirmButtonText: "Try Again"
        });
    });
});
</script>

</body>
</html>