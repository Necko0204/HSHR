<?php
include 'db_config.php';
session_start();

// Check if the expected POST variables are set
if (isset($_POST['sender_id'], $_POST['sender_role'], $_POST['receiver_id'], $_POST['receiver_role'], $_POST['message'])) {
    $sender_id = $_POST['sender_id'];
    $sender_role = $_POST['sender_role'];
    $receiver_id = $_POST['receiver_id'];
    $receiver_role = $_POST['receiver_role'];
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $message_id = uniqid('MSG-'); // Generate unique message ID
        $query = "INSERT INTO messages (id, sender_id, sender_type, receiver_id, receiver_type, message, status)
                  VALUES ('$message_id', '$sender_id', '$sender_role', '$receiver_id', '$receiver_role', '$message', 'sent')";
        if (mysqli_query($conn, $query)) {
            echo "Message sent successfully!";
        } else {
            echo "Error sending message: " . mysqli_error($conn);
        }
    } else {
        echo "Message cannot be empty!";
    }
} else {
    echo "Missing required data!";
}
?>
