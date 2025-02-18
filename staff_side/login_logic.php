<?php
session_name('staff_session');
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT employee_id, password, role FROM staff_accounts WHERE username = ?");
        if (!$stmt) {
            $_SESSION['error'] = "Database error: " . $conn->error;
            header("Location: index.php");
            exit();
        }

        $stmt->bind_param("s", $username);
        if (!$stmt->execute()) {
            $_SESSION['error'] = "Database error: " . $stmt->error;
            header("Location: index.php");
            exit();
        }

        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($employee_id, $stored_hashed_password, $role);
            $stmt->fetch();

            // Hash entered password with SHA-256
            $entered_hashed_password = hash('sha256', $password);

            if ($entered_hashed_password === $stored_hashed_password) {
                session_regenerate_id(true);
                $_SESSION['employee_id'] = $employee_id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role; // Add role to session
                header("Location: dashboard.php");
                exit();
            } else {
                $_SESSION['error'] = "❌ Incorrect username or password.";
            }
        } else {
            $_SESSION['error'] = "❌ Invalid username or password.";
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "❌ Please fill in all fields.";
    }

    $conn->close();
    header("Location: index.php"); // Redirect back to login
    exit();
}
?>
