<?php
session_name('admin_session');
session_start();
header('Content-Type: application/json'); // Ensure JSON response
require 'db_config.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Username and password are required."]);
        exit;
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, username, position, password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $hashedPassword = hash('sha256', $password);

        if ($hashedPassword === $row['password']) {
            // Set session variables
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_username'] = $row['username'];
            $_SESSION['position'] = $row['position'];

            // Ensure session is saved
            session_write_close();

            echo json_encode(["success" => true, "message" => "Login successful!", "session" => $_SESSION]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid password."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "No account found with that username."]);
    }

    $stmt->close();
    $conn->close();
}
?>
