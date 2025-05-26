<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container-fluid d-flex justify-content-end align-items-center gap-3">
    
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
    <ul class="dropdown-menu custom-dropdown" aria-labelledby="messageDropdown" id="messageList">
        <!-- Recent messages will be loaded here -->
        <li class="d-flex justify-content-center mt-2">
            <button class="btn btn-primary btn-sm rounded-circle" id="openMessageModal">
                <i class="fas fa-plus"></i>
            </button>
        </li>
    </ul>
</div>

<!-- Profile Dropdown -->
    <div class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" id="profileDropdown" data-bs-toggle="dropdown">
                <!-- Loading Spinner -->
        <div id="loading-spinner" style="width: 50px; height: 50px; display: block;">⏳</div>

        <!-- Profile Picture -->
        <?php
        $profilePic = isset($staffData['profile_picture']) ? $staffData['profile_picture'] : '/HSHR/images/default-profile.jpgy';
        ?>
        <img id="profile-pic" 
             src="<?php echo $profilePic; ?>?v=<?php echo time(); ?>" 
             alt="Profile Picture" 
             class="rounded-circle profile-pic me-2" 
             width="50" height="50" 
             style="display: none;" 
             onload="this.style.display='block'; document.getElementById('loading-spinner').style.display='none';">

        <div class="pulsing-icon2">
            <div class="pulsing-ring2"></div>
        </div>
        </a>
        <ul class="dropdown-menu custom-dropdown" aria-labelledby="profileDropdown" style="font-size: 1.15rem;">
            <li>
          <a class="dropdown-item d-flex align-items-center" href="staff_viewprofile.php">
              <i class="fas fa-user-circle me-2"></i>
              <span>View Profile</span>
          </a>
            </li>
            <li>
          <a class="dropdown-item d-flex align-items-center" href="staff_logout.php">
              <i class="fas fa-sign-out-alt me-2"></i>
              <span>Logout</span>
          </a>
            </li>
        </ul>
    </div>
  </div>
</nav>

<!-- Messenger Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow-lg">
      <!-- Modal Header -->
      <div class="modal-header text-white">
        <h5 class="modal-title" id="messageModalLabel">Messages</h5>
        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body d-flex" style="height: 450px;">
        <!-- Sidebar for user profiles -->
        <div class="user-sidebar p-3 border-end" style="width: 250px; overflow-y: auto; background: #f1f5f9; border-radius: 8px;">
          <h6 class="text-center mb-3">Users</h6>
          <ul class="list-unstyled" id="userList">
            <?php
            
              // Fetch users from the database
              include 'db_config.php';

              if (!isset($staffData['employee_id']) || !isset($staffData['role'])) {
                  echo "<p class='text-danger text-center'>User data is missing!</p>";
                  exit;
              }

              // Define logged-in staff ID and role
              $loggedInUserId = $staffData['employee_id'];
              $loggedInUserRole = $staffData['role'];

              // Query to fetch staff and admin users
              $query = "SELECT id, username, profile_picture, 'Administrator' AS role FROM admin 
                        UNION 
                        SELECT employee_id AS id, username, profile_picture, 'staff' AS role FROM staff_accounts";

              $result = mysqli_query($conn, $query);

              while ($row = mysqli_fetch_assoc($result)) {
                  if ($row['id'] == $loggedInUserId && $row['role'] == $loggedInUserRole) continue; // Skip logged-in user

                  echo '<li class="d-flex align-items-center mb-3 user-item" data-id="' . $row['id'] . '" data-role="' . $row['role'] . '" style="cursor: pointer;">';
                  echo '<img src="' . (!empty($row['profile_picture']) ? $row['profile_picture'] : 'uploads/profile_pictures/default.jpg') . '" class="rounded-circle me-2" width="50" height="50">';
                  echo '<span class="fw-semibold">' . htmlspecialchars($row['username']) . '</span>';
                  echo '</li>';
              }
            ?>
          </ul>
        </div>

        <!-- Chat Box -->
        <div class="chat-box flex-grow-1 d-flex flex-column bg-light p-3" id="chatBox" style="border-radius: 8px; overflow-y: auto;">
          <p class="text-muted text-center">Select a user to start chatting.</p>
        </div>
      </div>

      <!-- Modal Footer with input buttons -->
      <div class="modal-footer d-flex align-items-center">
        <div class="d-flex flex-grow-1 align-items-center">
          <!-- Message input -->
          <input type="text" class="form-control me-2" id="messageInput" placeholder="Type a message..." style="border-radius: 25px;">
          
        <!-- Send button -->
        <button class="btn btn-danger" id="sendMessage" style="border-radius: 25px;">
            Send <i class="bi bi-send"></i>
        </button>
        </div>

        <!-- File, Camera, and Voice Message buttons -->
        <div class="d-flex align-items-center ms-3">
          <button class="btn btn-outline-secondary me-2" id="insertFileButton" title="Insert File">
            <i class="bi bi-file-earmark"></i>
          </button>
          <button class="btn btn-outline-secondary me-2" id="cameraButton" title="Open Camera">
            <i class="bi bi-camera"></i>
          </button>
          <button class="btn btn-outline-secondary" id="voiceMessageButton" title="Record Voice">
            <i class="bi bi-mic"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Font Awesome (Make sure this is included in <head>) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

<!-- Bootstrap JS Bundle -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let currentReceiverId = null;
    let currentReceiverRole = null;
    let chatActive = false;
    
    const messageDropdown = document.getElementById("messageDropdown");
    const messageList = document.getElementById("messageList");
    const chatBox = document.getElementById("chatBox");
    const messageInput = document.getElementById("messageInput");
    const sendMessageBtn = document.getElementById("sendMessage");
    const messageModalElement = document.getElementById("messageModal");
    
    const messageModal = new bootstrap.Modal(messageModalElement);
    
    let senderId = "<?php echo isset($sender_id) ? $sender_id : ''; ?>";
    let senderRole = "<?php echo isset($sender_role) ? $sender_role : ''; ?>";
    
    console.log("Sender ID:", senderId);
    console.log("Sender Role:", senderRole);
    
    function loadRecentMessages() {
        fetch("includes/fetch_recent_messages.php")
            .then(response => response.text())
            .then(data => {
                messageList.innerHTML = data.trim() || ` 
                    <li class="d-flex justify-content-center mt-2">
                        <button class="btn btn-primary btn-sm rounded-circle" id="openMessageModal">
                            <i class="fas fa-plus"></i>
                        </button>
                    </li>`;
                attachEventListeners();
            })
            .catch(error => console.error("Error loading messages:", error));
    }
    
    function attachEventListeners() {
        document.addEventListener("click", function (event) {
            const user = event.target.closest(".user-item, .message-item");
            if (!user) return;
            
            currentReceiverId = user.getAttribute("data-id");
            currentReceiverRole = user.getAttribute("data-role");
            
            chatActive = true;
            messageModal.show();
            loadMessages(currentReceiverId, currentReceiverRole);
        });
    }
    
    function loadMessages(userId, userRole) {
        fetch(`includes/fetch_messages.php?receiver_id=${userId}&receiver_role=${userRole}`)
            .then(response => response.text())
            .then(data => {
                chatBox.innerHTML = data;
                setTimeout(() => {
                    chatBox.scrollTop = chatBox.scrollHeight;
                }, 100);
            })
            .catch(error => console.error("Error loading chat messages:", error));
    }
    
    sendMessageBtn.addEventListener("click", function () {
        const message = messageInput.value.trim();
        if (!message || !currentReceiverId) return;
        
        const formData = new FormData();
        formData.append("sender_id", senderId);
        formData.append("sender_role", senderRole);
        formData.append("receiver_id", currentReceiverId);
        formData.append("receiver_role", currentReceiverRole);
        formData.append("message", message);
        
        fetch("logics/send_message.php", { method: "POST", body: formData })
            .then(response => response.text())
            .then(() => {
                loadMessages(currentReceiverId, currentReceiverRole);
                messageInput.value = "";
            })
            .catch(error => console.log("Error sending message:", error));
    });
    
    messageModalElement.addEventListener("shown.bs.modal", () => chatActive = true);
    messageModalElement.addEventListener("hidden.bs.modal", () => chatActive = false);
    
    setInterval(() => {
        if (chatActive && currentReceiverId) {
            loadMessages(currentReceiverId, currentReceiverRole);
        }
    }, 3000);
    
    messageDropdown.addEventListener("click", loadRecentMessages);
    loadRecentMessages();
    
});

</script>

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
    

   
  });
  </script>