<?php
session_start();
include 'db_config.php'; // Ensure this file correctly sets up the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header("Location: index.php"); // Redirect back to login page
        exit();
    }

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, username, password, position FROM admin WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['position'] = $user['position'];

            // Redirect based on user role
            if ($user['position'] === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Invalid username or password.";
            header("Location: index.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid username or password.";
        header("Location: index.php");
        exit();
    }
}
?>
