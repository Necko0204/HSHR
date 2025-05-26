<?php
session_name('staff_session');
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_config.php';

header('Content-Type: application/json'); // Set response as JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        // Prepare SQL query to fetch user details
        $stmt = $conn->prepare("SELECT employee_id, password, role FROM staff_accounts WHERE username = ?");
        if (!$stmt) {
            echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
            exit();
        }

        $stmt->bind_param("s", $username);
        if (!$stmt->execute()) {
            echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
            exit();
        }

        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($employee_id, $stored_hashed_password, $role);
            $stmt->fetch();

            // ✅ Use password_verify() for bcrypt password comparison
            if (password_verify($password, $stored_hashed_password)) {
                session_regenerate_id(true);
                $_SESSION['employee_id'] = $employee_id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;

                $stmt->close();
                $conn->close();

                echo json_encode(["status" => "success", "message" => "✅ Login successful!", "redirect" => "dashboard.php"]);
                exit();
            } else {
                echo json_encode(["status" => "error", "message" => "❌ Incorrect username or password."]);
                exit();
            }
        } else {
            echo json_encode(["status" => "error", "message" => "❌ Invalid username or password."]);
            exit();
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "❌ Please fill in all fields."]);
        exit();
    }

    $conn->close();
}
?>