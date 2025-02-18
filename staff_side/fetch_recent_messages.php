<?php
session_name('staff_session');
session_start();
include 'db_config.php';

// Check if user is logged in
if (!isset($_SESSION['employee_id']) || !isset($_SESSION['role'])) {
    echo "<li><a class='dropdown-item' href='#'>Error: Not logged in</a></li>";
    exit;
}

$employee_id = $_SESSION['employee_id'];
$role = $_SESSION['role'];

// Fetch the latest message per conversation
$query = "SELECT m.*, 
                 CASE 
                    WHEN m.sender_id = '$employee_id' THEN m.receiver_id
                    ELSE m.sender_id
                 END AS user_id,
                 CASE 
                    WHEN m.sender_id = '$employee_id' THEN m.receiver_type
                    ELSE m.sender_type
                 END AS user_role,
                 COALESCE(a.username, s.username) AS username,
                 COALESCE(a.profile_picture, s.profile_picture, 'uploads/profile_pictures/default.jpg') AS profile_picture
          FROM messages m
          LEFT JOIN admin a ON a.id = (CASE WHEN m.sender_id = '$employee_id' THEN m.receiver_id ELSE m.sender_id END)
          LEFT JOIN staff_accounts s ON s.employee_id = (CASE WHEN m.sender_id = '$employee_id' THEN m.receiver_id ELSE m.sender_id END)
          WHERE m.id IN (
              SELECT MAX(id) 
              FROM messages 
              WHERE sender_id = '$employee_id' OR receiver_id = '$employee_id' 
              GROUP BY LEAST(sender_id, receiver_id), GREATEST(sender_id, receiver_id)
          )
          ORDER BY m.sent_at DESC";

$result = mysqli_query($conn, $query);

// Check if any messages exist
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $user_id = $row['user_id'];
        $user_role = $row['user_role'];
        $username = htmlspecialchars($row['username']);
        $profile_picture = htmlspecialchars($row['profile_picture']);
        $last_message = htmlspecialchars($row['message']);
        $short_message = (strlen($last_message) > 25) ? substr($last_message, 0, 25) . '...' : $last_message;

        echo "<li class='dropdown-item d-flex align-items-center message-item' data-id='$user_id' data-role='$user_role'>
                <img src='$profile_picture' class='rounded-circle me-2' width='40' height='40'>
                <div>
                    <strong>$username</strong>
                    <p class='text-muted mb-0' style='font-size: 12px;'>$short_message</p>
                </div>
              </li>";
    }
} else {
    // If no messages, show the plus button
    echo "<p style='text-align: center;'>No Messages</p>
    <li class='d-flex justify-content-center mt-2'>
            <button class='btn btn-primary btn-sm rounded-circle' id='openMessageModal'>
                <i class='fas fa-plus'></i>
            </button>
          </li>";
}
?>
