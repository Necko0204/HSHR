<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container-fluid d-flex justify-content-end align-items-center gap-3">
    
    <!-- Dark Mode Toggle Button -->
    <button id="darkModeToggle" class="btn btn-outline-secondary border-0">
      <i id="darkModeIcon" class="fas fa-moon"></i>
    </button>

    <!-- Notification Dropdown -->
    <div class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" id="notificationDropdown" data-bs-toggle="dropdown">
            <i class="fas fa-bell"></i> 
        </a>
        <ul class="dropdown-menu custom-dropdown" aria-labelledby="notificationDropdown">
            <li><a class="dropdown-item" href="#">No Notifications</a></li>
        </ul>
    </div>
        
    <!-- Message Dropdown -->
    <div class="nav-item dropdown">
      <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" id="messageDropdown" data-bs-toggle="dropdown">
        <i class="fas fa-envelope"></i> 
      </a>
      <ul class="dropdown-menu custom-dropdown" aria-labelledby="messageDropdown">
        <li><a class="dropdown-item" href="#">No Messages</a></li>
      </ul>
    </div>

    <!-- Profile Dropdown -->
    <div class="nav-item dropdown">
      <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" id="profileDropdown" data-bs-toggle="dropdown">
        <img src="<?php echo !empty($userData['profile_picture']) ? $userData['profile_picture'] : 'uploads/profile_pictures/default.jpg'; ?>" 
        alt="Profile Picture" 
        class="rounded-circle profile-pic">
        <div class="pulsing-icon2">
          <div class="pulsing-ring2"></div>
        </div>
      </a>
      <ul class="dropdown-menu custom-dropdown" aria-labelledby="profileDropdown">
        <li><a class="dropdown-item" href="view_profile.php">View Profile</a></li>
        <li><a class="dropdown-item" href="staff_logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Font Awesome (Make sure this is included in <head>) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
  #darkModeToggle:hover {
    opacity: 0.7; /* Slight transparency on hover */
    cursor: pointer; /* Changes cursor to indicate clickability */
}

/* Dark Mode Styles */
body.dark-mode {
    background-color: #121212;
    color: #ffffff;
}

/* Navbar Dark Mode */
.navbar.dark-mode {
    background-color: #333333;
    color: #ffffff;
    box-shadow: 0px 4px 10px rgba(255, 255, 255, 0.1);
}

/* Table Body Dark Mode */
.table.dark-mode tbody {
    background-color: #1e1e1e;
    color: #ffffff;
}

/* Table Rows Dark Mode */
.table.dark-mode tr {
    border-color: #444444;
}

/* Table Header and Cells Dark Mode */
.table.dark-mode th, .table.dark-mode td {
    background-color: #222222;
    color: #e0e0e0;
    border-color: #444444;
}

/* Hover Effect for Table Rows */
.table.dark-mode tbody tr:hover {
    background-color: #333333;
}

/* Improve Readability for Lighter Text */
.table.dark-mode td {
    color: #dddddd;
}


/* Card Dark Mode */
.card.dark-mode, .modal-content.dark-mode {
    background-color: #222222;
    color: #ffffff;
    border: 1px solid #444444;
}

/* Card & Modal Header/Footer Dark Mode */
.card-header.dark-mode, .modal-header.dark-mode, .modal-footer.dark-mode {
    background-color: #333333;
    color: #ffffff;
    border-bottom: 1px solid #444444;
}

/* Typography */
h1.dark-mode, h2.dark-mode, h3.dark-mode, h4.dark-mode, h5.dark-mode, h6.dark-mode,
p.dark-mode, span.dark-mode, a.dark-mode {
    color: #cccccc;
}

/* Buttons */
.btn-outline-secondary.dark-mode {
    border-color: #ffffff;
    color: #ffffff;
}
.btn-outline-secondary.dark-mode:hover {
    background-color: #ffffff;
    color: #121212;
}

/* Dropdown Dark Mode */
.custom-dropdown.dark-mode {
    background: linear-gradient(135deg, #222222, #333333);
    box-shadow: 0 8px 16px rgba(255, 255, 255, 0.1);
}
.custom-dropdown .dropdown-item.dark-mode {
    color: #ffffff;
}
.custom-dropdown .dropdown-item.dark-mode:hover {
    background-color: #444444;
}

/* Navbar & Sidebar */
.navbar {
    background-color:rgb(139, 41, 41);
    top: 0;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    padding: 15px 20px;
    
}

/* Profile Picture */
.profile-pic {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid #ddd;
    object-fit: cover;
}

/* Custom Dropdown Styling */
.custom-dropdown {
    min-width: 200px;
    max-width: 90vw;
    background: linear-gradient(135deg, #ffffff, #f9f9f9);
    border-radius: 10px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    margin-top: 10px;
    animation: fadeInScale 0.3s ease forwards;
    right: 0;
    left: auto !important;
}
.dropdown-menu {
    right: 0 !important;
    left: auto !important;
    transform: translateX(0) !important;
}
.custom-dropdown .dropdown-item {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Dropdown Animation */
@keyframes fadeInScale {
    0% { opacity: 0; transform: scale(0.95); }
    100% { opacity: 1; transform: scale(1); }
}

/* Pulsing Notification */
.pulsing-icon2 {
    width: 12px;
    height: 12px;
    background-color: green;
    border-radius: 50%;
    position: relative;
    z-index: 2;
    top: 13px;
    left: -10px;
}
.pulsing-ring2 {
    position: absolute;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: 2px solid rgba(0, 128, 0, 0.5);
    animation: pulse-ring 1.5s infinite;
    top: -35%;
    right: -42%;
    transform: translate(-50%, -50%);
}
@keyframes pulse-ring {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.5); opacity: 0.5; }
    100% { transform: scale(2); opacity: 0; }
}
</style>

<!-- Font Awesome CDN (Include in your <head> if not already) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const toggleButton = document.getElementById("darkModeToggle");
    const darkModeIcon = document.getElementById("darkModeIcon");

    function toggleDarkMode(isDarkMode) {
      document.body.classList.toggle("dark-mode", isDarkMode);
      document.querySelector(".navbar")?.classList.toggle("dark-mode", isDarkMode);

      document.querySelectorAll(".card, .card-header, h1, h2, h3, h4, h5, h6, p, span, a, .custom-dropdown, .dropdown-item, .btn-outline-secondary, .modal-content")
        .forEach(el => el.classList.toggle("dark-mode", isDarkMode));

      document.querySelector(".sidebar")?.classList.toggle("dark-mode", isDarkMode);

      fetch("dark_mode.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "dark_mode=" + isDarkMode
      });

      // Update icon color
      darkModeIcon.style.color = isDarkMode ? "#dddddd" : "#000000";

      // Update icon
      darkModeIcon.classList.toggle("fa-moon", !isDarkMode);
      darkModeIcon.classList.toggle("fa-sun", isDarkMode);
    }

    // Fetch stored dark mode preference
    fetch("dark_mode.php", { method: "POST" })
      .then(response => response.json())
      .then(data => {
        if (data.dark_mode) {
          toggleDarkMode(true);
        }
      });

    toggleButton.addEventListener("click", function () {
      const isDarkMode = document.body.classList.contains("dark-mode");
      toggleDarkMode(!isDarkMode);
    });
  });
  </script>