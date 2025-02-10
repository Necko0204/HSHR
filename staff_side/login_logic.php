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
        $stmt = $conn->prepare("SELECT employee_id, password FROM staff_accounts WHERE username = ?");
        if (!$stmt) {
            die("❌ Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $username);
        if (!$stmt->execute()) {
            die("❌ Execute failed: " . $stmt->error);
        }

        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($employee_id, $stored_hashed_password);
            $stmt->fetch();

            // Hash entered password with SHA-256
            $entered_hashed_password = hash('sha256', $password);

            if ($entered_hashed_password === $stored_hashed_password) {
                session_regenerate_id(true);
                $_SESSION['employee_id'] = $employee_id;
                $_SESSION['username'] = $username;
                header("Location: dashboard.php");
                exit();
            } else {
                die("❌ Incorrect username or password.");
            }
        } else {
            die("❌ Invalid username or password.");
        }
        $stmt->close();
    } else {
        die("❌ Please fill in all fields.");
    }

    $conn->close();
}
?>
