<?php
session_name('staff_session');  
session_start();  

include 'db_config.php';  

// Validate session variables
if (!isset($_SESSION['employee_id']) || !isset($_SESSION['role'])) {
    echo "<p class='text-muted text-center'>Error: Not logged in</p>";
    exit;
}

if (!isset($_GET['receiver_id']) || !isset($_GET['receiver_role'])) {
    echo "<p class='text-muted text-center'>Error: Missing receiver data</p>";
    exit;
}

// Sanitize inputs
$sender_id = trim(mysqli_real_escape_string($conn, $_SESSION['employee_id']));
$sender_role = trim(mysqli_real_escape_string($conn, $_SESSION['role']));
$receiver_id = trim(mysqli_real_escape_string($conn, $_GET['receiver_id']));
$receiver_role = trim(mysqli_real_escape_string($conn, $_GET['receiver_role']));

// Fetch messages with correct profile picture
$query = "SELECT m.*, 
                 CASE 
                    WHEN m.sender_id = '$sender_id' THEN 'sent' 
                    ELSE 'received' 
                 END AS message_type,
                 COALESCE(
                    (SELECT profile_picture FROM admin WHERE id = m.sender_id),
                    (SELECT profile_picture FROM staff_accounts WHERE employee_id = m.sender_id),
                    'uploads/profile_pictures/default.jpg'
                 ) AS profile_picture
          FROM messages m
          WHERE 
            (m.sender_id = '$sender_id' AND m.sender_type = '$sender_role' 
             AND m.receiver_id = '$receiver_id' AND m.receiver_type = '$receiver_role')
            OR 
            (m.sender_id = '$receiver_id' AND m.sender_type = '$receiver_role' 
             AND m.receiver_id = '$sender_id' AND m.receiver_type = '$sender_role') 
          ORDER BY m.sent_at ASC";

$result = mysqli_query($conn, $query);

// Error handling
if (!$result) {
    echo "<p class='text-danger text-center'>Error loading messages.</p>";
    exit;
}

// Display messages
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $message = htmlspecialchars($row['message']);
        $timestamp = date("h:i A", strtotime($row['sent_at']));
        $profile_picture = !empty($row['profile_picture']) ? htmlspecialchars($row['profile_picture']) : 'uploads/profile_pictures/default.jpg';
        $message_type = $row['message_type'];

        if ($message_type == 'sent') {
            // Sent message (align to right)
            echo "<div class='d-flex justify-content-end mb-3'>
                    <div class='p-2 bg-primary text-white rounded w-75' style='max-width: 75%; border-radius: 10px;'>
                        <p class='mb-1'>$message</p>
                        <small class='text-light text-end d-block'>$timestamp</small>
                    </div>
                    <img src='$profile_picture' class='rounded-circle ms-2' width='40' height='40' onerror=\"this.src='uploads/profile_pictures/default.jpg';\">
                  </div>";
        } else {
            // Received message (align to left)
            echo "<div class='d-flex justify-content-start mb-3'>
                    <img src='$profile_picture' class='rounded-circle me-2' width='40' height='40' onerror=\"this.src='uploads/profile_pictures/default.jpg';\">
                    <div class='p-2 bg-light text-dark rounded w-75' style='max-width: 75%; border-radius: 10px;'>
                        <p class='mb-1'>$message</p>
                        <small class='text-muted text-end d-block'>$timestamp</small>
                    </div>
                  </div>";
        }
    }
} else {
    echo "<p class='text-muted text-center'>No messages yet. Start the conversation!</p>";
}
?>
