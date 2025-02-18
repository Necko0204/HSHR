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
            <img src="<?php echo isset($staffData) && !empty($staffData['profile_picture']) 
                ? '../' . htmlspecialchars($staffData['profile_picture']) 
                : '/HSHR/images/default.jpg'; ?>" 
                alt="Profile Picture" 
                class="rounded-circle profile-pic">
            <div class="pulsing-icon2">
                <div class="pulsing-ring2"></div>
            </div>
        </a>
        <ul class="dropdown-menu custom-dropdown" aria-labelledby="profileDropdown">
            <li><a class="dropdown-item" href="staff_viewprofile.php">View Profile</a></li>
            <li><a class="dropdown-item" href="staff_logout.php">Logout</a></li>
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
    const messageDropdown = document.getElementById("messageDropdown");
    const messageList = document.getElementById("messageList");
    const chatBox = document.getElementById("chatBox");
    const messageInput = document.getElementById("messageInput");
    const sendMessageBtn = document.getElementById("sendMessage");
    const messageModalElement = document.getElementById("messageModal");

    // Initialize Bootstrap modal
    const messageModal = new bootstrap.Modal(messageModalElement);

    // Get sender details from PHP safely
    let senderId = "<?php echo isset($sender_id) ? $sender_id : ''; ?>";
    let senderRole = "<?php echo isset($sender_role) ? $sender_role : ''; ?>";

    // Log to check if senderId and senderRole are set correctly
    console.log("Sender ID:", senderId);
    console.log("Sender Role:", senderRole);

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
                loadMessages(currentReceiverId, currentReceiverRole);
            });
        });

        // Open the modal when clicking the button to compose a new message
        const plusButton = document.getElementById("openMessageModal");
        if (plusButton) {
            plusButton.addEventListener("click", () => messageModal.show());
        }
    }

    // Load messages for a specific user
    function loadMessages(userId, userRole) {
    fetch(`fetch_messages.php?receiver_id=${userId}&receiver_role=${userRole}`)
        .then(response => response.text())
        .then(data => {
            chatBox.innerHTML = data;

            // Auto-scroll to the bottom after loading messages
            setTimeout(() => {
                chatBox.scrollTop = chatBox.scrollHeight;
            }, 100);
        })
        .catch(error => console.error("Error loading chat messages:", error));
}

    // Send message via AJAX
    sendMessageBtn.addEventListener("click", function () {
        const message = messageInput.value.trim();
        if (!message || !currentReceiverId) return;

        const formData = new FormData();
        formData.append("sender_id", senderId);
        formData.append("sender_role", senderRole);
        formData.append("receiver_id", currentReceiverId);
        formData.append("receiver_role", currentReceiverRole);
        formData.append("message", message);

        fetch("send_message.php", { method: "POST", body: formData })
            .then(response => response.text())
            .then(() => {
                loadMessages(currentReceiverId, currentReceiverRole);
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
        loadMessages(currentReceiverId, currentReceiverRole);
    });
});
</script>

<style>

  /* Enhanced Chat Modal Styles */
#chatBox {
    scroll-behavior: smooth;
    padding: 10px;
}

.user-item:hover {
    background-color: #dee2e6;
    border-radius: 10px;
    transition: background 0.3s ease;
}

button {
    transition: all 0.3s ease;
}

button:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

.input-buttons button {
    padding: 8px;
    border-radius: 50%;
    background-color: #f8f9fa;
    border: none;
    cursor: pointer;
    transition: background 0.3s ease;
}

.input-buttons button:hover {
    background-color: #e2e6ea;
}

.input-buttons i {
    font-size: 20px;
    color:rgb(255, 39, 39);
}

.message {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
    width: 100%;
}

.message.sent {
    justify-content: flex-end;
}

.message.received {
    justify-content: flex-start;
}

.message .profile-picture {
    border-radius: 50%;
    margin: 0 10px;
}

.message .text {
    max-width: 75%;
    background-color: #f8f9fa;
    padding: 12px;
    border-radius: 12px;
    font-size: 14px;
}

.message.sent .text {
    background-color:rgb(201, 41, 36);
    color: white;
}

.message.received .text {
    background-color: #e9ecef;
    color: black;
}

.chat-box {
    overflow-y: auto;
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.chat-box::-webkit-scrollbar {
    display: none;
}

.modal-header {
    border-bottom: none;
    background: linear-gradient(135deg,rgb(139, 0, 0), black);
}

.modal-footer {
    border-top: none;
    background: #f8f9fa;
}

#messageInput {
    border-radius: 30px;
    padding: 10px 15px;
}

#sendMessage {
    border-radius: 30px;
    padding: 8px 20px;
    font-weight: 600;
}
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
    background-color:rgb(218, 255, 255);
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