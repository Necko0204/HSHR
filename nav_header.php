<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container-fluid d-flex justify-content-end align-items-center gap-3">
    
    <!-- Notification Dropdown -->
    <div class="nav-item dropdown">
      <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell"></i> 
      </a>
      <ul class="dropdown-menu custom-dropdown" aria-labelledby="notificationDropdown">
        <li><a class="dropdown-item" href="#">No Notifications</a></li>
      </ul>
    </div>
    
    <!-- Message Dropdown -->
    <div class="nav-item dropdown">
      <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" id="messageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-envelope"></i> 
      </a>
      <ul class="dropdown-menu custom-dropdown" aria-labelledby="messageDropdown">
        <li><a class="dropdown-item" href="#">No Messages</a></li>
      </ul>
    </div>

    <!-- Profile Dropdown -->
    <div class="nav-item dropdown">
      <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
      <img src="<?php echo !empty($userData['profile_picture']) ? $userData['profile_picture'] : 'uploads/profile_pictures/default.jpg'; ?>" 
      alt="Profile Picture" 
      class="rounded-circle profile-pic">
      <div class="pulsing-icon2">
        <div class="pulsing-ring2"></div>
        </div>
      </a>
      <ul class="dropdown-menu custom-dropdown" aria-labelledby="profileDropdown">
      <li><a class="dropdown-item" href="view_profile.php">View Profile</a></li>
      <li><a class="dropdown-item" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>


<style>
  /* Navbar Base */
  .navbar {
    position: fixed;
    top: 0;
    left: 260px; /* Adjust based on your sidebar width */
    width: calc(100% - 260px); /* Ensure no overlap with sidebar */
    background-color: #ffffff;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    z-index: 100;
    padding: 15px 20px;
    transition: all 0.3s ease;
  }

  /* Sidebar width adjustment */
  .sidebar {
    width: 260px; /* Set based on your actual sidebar width */
    position: fixed;
    height: 100vh;
    background-color: #d9534f;
  }

  /* Adjust profile picture size in the navbar */
.profile-pic {
    width: 40px;  /* Adjust as needed */
    height: 40px; /* Ensure it maintains a circular shape */
    border-radius: 50%;
    border: 2px solid #ddd;
    object-fit: cover; /* Ensures the image is cropped properly */
}


  /* Custom Dropdown Styling */
  .custom-dropdown {
    min-width: 200px;
    background: linear-gradient(135deg, #ffffff, #f9f9f9);
    border: none;
    border-radius: 10px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    margin-top: 10px;
    animation: fadeInScale 0.3s ease forwards;
  }

  /* Dropdown Animation */
  @keyframes fadeInScale {
    0% {
      opacity: 0;
      transform: scale(0.95);
    }
    100% {
      opacity: 1;
      transform: scale(1);
    }
  }

  /* Ensure dropdown stays within viewport */
  .custom-dropdown {
    min-width: 200px;
    max-width: 90vw; /* Prevents the dropdown from exceeding screen width */
    background: linear-gradient(135deg, #ffffff, #f9f9f9);
    border: none;
    border-radius: 10px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    margin-top: 10px;
    animation: fadeInScale 0.3s ease forwards;
    right: 0; /* Ensures it stays within the viewport */
    left: auto !important; /* Prevents Bootstrap default left alignment */
  }

  /* Force alignment inside the viewport */
  .dropdown-menu {
    right: 0 !important;
    left: auto !important;
    transform: translateX(0) !important;
  }

  /* Ensure text does not break the container */
  .custom-dropdown .dropdown-item {
    white-space: nowrap; /* Prevents long text from breaking layout */
    overflow: hidden;
    text-overflow: ellipsis;
  }
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
