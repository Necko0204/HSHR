<?php
session_name('staff_session');
session_start();
include 'staff_helper.php';
include 'db_config.php';

if (!isset($_SESSION['employee_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch staff data
$employee_id = $_SESSION['employee_id'];

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
    <link rel="stylesheet" href="styles.css">
    
</head>
<body>

<?php include 'staff_navbar.php'; ?>



<!-- Back to Dashboard Button -->
<div style="position: absolute; top: 7px; left: 20px; z-index: 1000;">
    <a href="dashboard.php" class="btn btn-secondary">
        <i class="fa fa-arrow-left"></i> Back to Dashboard
    </a>    
</div>
<main class="wrapper d-flex justify-content-center align-items-center text-center" style="min-height: 100vh; overflow: hidden;">
    <!-- Background Editor -->
    <div class="background-editor p-4 rounded shadow" style="background-color: #fff; max-width: 400px; width: 100%; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);">
        <h5 class="text-center mb-4">Background Editor</h5>
        <div class="mb-3">
            <label for="bgColorStart" class="form-label">Background Gradient Start:</label>
            <input type="color" id="bgColorStart" class="form-control">
        </div>
        <div class="mb-3">
            <label for="bgColorEnd" class="form-label">Background Gradient End:</label>
            <input type="color" id="bgColorEnd" class="form-control">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" id="toggleShapes" class="form-check-input" checked>
            <label for="toggleShapes" class="form-check-label">Enable Background Shapes</label>
        </div>
        <div class="mb-3">
            <label for="navbarColor" class="form-label">Navbar Color:</label>
            <input type="color" id="navbarColor" class="form-control">
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const bgColorStartPicker = document.getElementById("bgColorStart");
    const bgColorEndPicker = document.getElementById("bgColorEnd");
    const toggleShapes = document.getElementById("toggleShapes");
    const navbarColorPicker = document.getElementById("navbarColor");
    const shapesContainer = document.createElement('div');
    shapesContainer.classList.add('floating-container');
    document.body.appendChild(shapesContainer);
    const navbar = document.querySelector(".navbar");
    
    // Load saved preferences
    const savedBgColorStart = localStorage.getItem("bgColorStart");
    const savedBgColorEnd = localStorage.getItem("bgColorEnd");
    const savedNavbarColor = localStorage.getItem("navbarColor");
    const savedShapesEnabled = localStorage.getItem("shapesEnabled");

    if (savedBgColorStart && savedBgColorEnd) {
        shapesContainer.style.background = `linear-gradient(120deg, ${savedBgColorStart}, ${savedBgColorEnd})`;
        bgColorStartPicker.value = savedBgColorStart;
        bgColorEndPicker.value = savedBgColorEnd;
    }
    if (savedNavbarColor) {
        navbar.style.backgroundColor = savedNavbarColor;
        navbarColorPicker.value = savedNavbarColor;
    }
    if (savedShapesEnabled !== null) {
        toggleShapes.checked = savedShapesEnabled === "true";
        shapesContainer.style.display = toggleShapes.checked ? "block" : "none";
    }
    
    function updateBackground() {
        shapesContainer.style.background = `linear-gradient(120deg, ${bgColorStartPicker.value}, ${bgColorEndPicker.value})`;
        localStorage.setItem("bgColorStart", bgColorStartPicker.value);
        localStorage.setItem("bgColorEnd", bgColorEndPicker.value);
    }
    bgColorStartPicker.addEventListener("input", updateBackground);
    bgColorEndPicker.addEventListener("input", updateBackground);
    
    toggleShapes.addEventListener("change", function() {
        shapesContainer.style.display = toggleShapes.checked ? "block" : "none";
        localStorage.setItem("shapesEnabled", toggleShapes.checked);
    });
    
    navbarColorPicker.addEventListener("input", function() {
        navbar.style.backgroundColor = navbarColorPicker.value;
        localStorage.setItem("navbarColor", navbarColorPicker.value);
    });

    function createFloatingShapes() {
        const colors = ["#6a86d8", "#4b6584", "#b3cde0", "#2e4053", "#5d6d7e"];
        const shapes = ["circle", "square", "triangle"];
        for (let i = 0; i < 40; i++) {
            let shape = document.createElement("div");
            shape.classList.add("floating-shape");
            let randomShape = shapes[Math.floor(Math.random() * shapes.length)];
            shape.classList.add(randomShape);
            let color = colors[Math.floor(Math.random() * colors.length)];
            if (randomShape === "triangle") {
                shape.style.borderBottomColor = color;
            } else {
                shape.style.backgroundColor = color;
            }
            let size = Math.random() * 30 + 15;
            shape.style.width = size + "px";
            shape.style.height = size + "px";
            shape.style.top = Math.random() * 100 + "vh";
            shape.style.left = Math.random() * 100 + "vw";
            shape.style.animationDuration = (Math.random() * 4 + 4) + "s";
            shape.style.animationDelay = Math.random() * 2 + "s";
            shapesContainer.appendChild(shape);
        }
    }

    if (toggleShapes.checked) {
        createFloatingShapes();
    }
});
</script>

</body>
</html>