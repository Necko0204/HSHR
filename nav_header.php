    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <div class="navbar-nav ms-auto">
                <div class="nav-item">
                    <a href="#" class="nav-link"><i class="fas fa-bell"></i></a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link"><i class="fas fa-envelope"></i></a>
                </div>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown">
                        <img src="profile_picture.jpg" alt="Profile" class="profile-pic">
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="view_profile.php">View Profile</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <style>
        .navbar {
            position: fixed;
            top: 0;
            left: 260px; /* Adjust this value based on your sidebar width */
            width: calc(100% - 250px); /* Make sure it doesn't overlap the sidebar */
            background-color: #ffffff;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            z-index: 100;
            padding: 15px 20px;
            transition: all 0.3s ease; /* Smooth transition */
        }

        /* Sidebar width adjustment */
        .sidebar {
            width: 260px; /* Set this width based on your actual sidebar width */
            position: fixed;
            height: 100vh;
            background-color: #d9534f;
        }
    </style>