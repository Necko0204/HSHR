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

<!-- Messenger Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow-lg">
      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="messageModalLabel">Messages</h5>
        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body d-flex" style="height: 450px;">
        <!-- Sidebar for user profiles -->
        <div class="user-sidebar p-3 border-end" style="width: 250px; overflow-y: auto; background: #f1f5f9; border-radius: 8px;">
          <h6 class="text-center mb-3">Users</h6>
          <ul class="list-unstyled" id="userList">
            <?php
              include 'db_config.php';
              $sender_id = $_SESSION['admin_id'];
              $sender_role = $_SESSION['position'];
              if (!$sender_id || !$sender_role) {
                  echo "Sender ID or role is missing!";
                  exit;
              }
              $query = "SELECT id, username, profile_picture, 'Administrator' AS role FROM admin 
                        UNION 
                        SELECT employee_id AS id, username, profile_picture, 'staff' AS role FROM staff_accounts";
              $result = mysqli_query($conn, $query);
              while ($row = mysqli_fetch_assoc($result)) {
                  if ($row['id'] == $sender_id && $row['role'] == $sender_role) continue;
                  echo '<li class="d-flex align-items-center mb-3 user-item" data-id="' . $row['id'] . '" data-role="' . $row['role'] . '" style="cursor: pointer;">';
                  echo '<img src="' . (!empty($row['profile_picture']) ? $row['profile_picture'] : 'uploads/profile_pictures/default.jpg') . '" class="rounded-circle me-2" width="50" height="50">';
                  echo '<span class="fw-semibold">' . htmlspecialchars($row['username']) . '</span>';
                  echo '</li>';
              }
            ?>
          </ul>
        </div>

        <!-- Chat Box -->
        <div class="chat-box flex-grow-1 d-flex flex-column bg-light p-3 position-relative" id="chatBox" style="border-radius: 8px; overflow-y: auto;">
            <p class="text-muted text-center">Select a user to start chatting.</p>
        </div>


      </div>
      
      <!-- Modal Footer with input buttons -->
      <div class="modal-footer d-flex align-items-center">
        <div class="d-flex flex-grow-1 align-items-center">
          <input type="text" class="form-control me-2" id="messageInput" placeholder="Type a message..." style="border-radius: 25px;">
          <button class="btn btn-danger" id="sendMessage" style="border-radius: 25px;">Send <i class="bi bi-send"></i></button>
        </div>
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


<script>
document.addEventListener("DOMContentLoaded", function () {
    // Initialize variables
    let currentReceiverId = null;
    let currentReceiverRole = null;
    const messageDropdown = document.getElementById("messageDropdown");
    const messageList = document.getElementById("messageList");
    const chatBox = document.getElementById("chatBox");
    const messageInput = document.getElementById("messageInput");
    const sendMessageBtn = document.getElementById("sendMessage");
    const messageModalElement = document.getElementById("messageModal");
    const messageModal = new bootstrap.Modal(messageModalElement);

    // Function to load recent messages
    function loadRecentMessages() {
        fetch("fetch_recent_messages.php")
            .then(response => response.text())
            .then(data => {
                messageList.innerHTML = data.trim() || `
                    <li class="d-flex justify-content-center mt-2">
                        <button class="btn btn-primary btn-sm rounded-circle" id="openMessageModal">
                            <i class="fas fa-plus"></i>
                        </button>
                    </li>`;
                attachEventListeners(); // Rebind click events
            })
            .catch(error => console.error("Error loading messages:", error));
    }

    // Attach event listeners dynamically
    function attachEventListeners() {
        document.querySelectorAll(".user-item, .message-item").forEach(item => {
            item.addEventListener("click", function () {
                currentReceiverId = item.getAttribute("data-id");
                currentReceiverRole = item.getAttribute("data-role");
                messageModal.show();
                loadMessages(currentReceiverId, currentReceiverRole, true);
            });
        });

        const plusButton = document.getElementById("openMessageModal");
        if (plusButton) {
            plusButton.addEventListener("click", () => messageModal.show());
        }
    }

    // Load messages for a specific user
    function loadMessages(userId, userRole, scrollToBottom = false) {
        fetch(`fetch_messages.php?receiver_id=${userId}&receiver_role=${userRole}`)
            .then(response => response.text())
            .then(data => {
                chatBox.innerHTML = data;

                // Scroll to the bottom if specified
                if (scrollToBottom) {
                    setTimeout(() => {
                        chatBox.scrollTop = chatBox.scrollHeight;
                    }, 100);
                }
            })
            .catch(error => console.error("Error loading chat messages:", error));
    }

    // Send message via AJAX
    sendMessageBtn.addEventListener("click", function () {
        const message = messageInput.value.trim();
        if (!message || !currentReceiverId) return;

        const formData = new FormData();
        formData.append("sender_id", <?php echo json_encode($sender_id); ?>);
        formData.append("sender_role", <?php echo json_encode($sender_role); ?>);
        formData.append("receiver_id", currentReceiverId);
        formData.append("receiver_role", currentReceiverRole);
        formData.append("message", message);

        fetch("send_message.php", { method: "POST", body: formData })
            .then(response => response.text())
            .then(() => {
                loadMessages(currentReceiverId, currentReceiverRole, true);
                messageInput.value = "";
            })
            .catch(error => console.log("Error sending message:", error));
    });

    // Auto-refresh chat messages every second
    setInterval(() => {
        if (currentReceiverId) {
            loadMessages(currentReceiverId, currentReceiverRole);
        }
    }, 1000);

    // Handle file upload
    document.getElementById("insertFileButton").addEventListener("click", function () {
        const fileInput = document.createElement("input");
        fileInput.type = "file";
        fileInput.accept = "*/*";  
        fileInput.click();

        fileInput.addEventListener("change", function () {
            const file = fileInput.files[0];
            if (file) {
                console.log("File selected:", file.name);
            }
        });
    });

    // Handle camera access
    document.getElementById("cameraButton").addEventListener("click", function () {
        if (navigator.mediaDevices?.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(stream => {
                    const videoElement = document.createElement("video");
                    videoElement.srcObject = stream;
                    videoElement.play();
                    document.body.appendChild(videoElement);
                    console.log("Camera is ready to use!");
                })
                .catch(error => console.error("Camera error:", error));
        }
    });

    // Handle voice message recording
    document.getElementById("voiceMessageButton").addEventListener("click", function () {
        if (navigator.mediaDevices?.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ audio: true })
                .then(stream => {
                    const mediaRecorder = new MediaRecorder(stream);
                    const audioChunks = [];

                    mediaRecorder.ondataavailable = event => audioChunks.push(event.data);
                    mediaRecorder.onstop = () => {
                        const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                        const audioURL = URL.createObjectURL(audioBlob);
                        new Audio(audioURL).play();
                        console.log("Voice message recorded and played.");
                    };

                    mediaRecorder.start();
                    setTimeout(() => mediaRecorder.stop(), 5000);
                })
                .catch(error => console.error("Microphone error:", error));
        }
    });

    // Load messages when dropdown is clicked
    messageDropdown.addEventListener("click", loadRecentMessages);

    // Initial load of recent messages
    loadRecentMessages();

    // Event listener for dynamically loaded user list
    document.getElementById("userList").addEventListener("click", function (event) {
        const user = event.target.closest(".user-item");
        if (!user) return;

        currentReceiverId = user.getAttribute("data-id");
        currentReceiverRole = user.getAttribute("data-role");

        messageModal.show();
        loadMessages(currentReceiverId, currentReceiverRole, true);
    });

    // Ensure chat scrolls to bottom when modal is shown
    messageModalElement.addEventListener('shown.bs.modal', function () {
        if (currentReceiverId) {
            loadMessages(currentReceiverId, currentReceiverRole, true);
        }
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const chatBox = document.getElementById('chatBox');
    
    for (let i = 0; i < 5; i++) {
        const box = document.createElement('div');
        box.classList.add('box');
        chatBox.appendChild(box);
    }
});

</script>

<!-- Font Awesome (Make sure this is included in <head>) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


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