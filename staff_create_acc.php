<?php
require 'db_config.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $_POST['employee_id'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    $profile_picture = $_FILES['profile_picture'] ?? null;

    // Validate required fields
    if (empty($employee_id) || empty($username) || empty($password) || empty($role)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

// Check if username already exists
$stmt = $conn->prepare("SELECT id FROM staff_accounts WHERE username = ?");
$stmt->bind_param("s", $username); // Corrected "s" for string
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Username already taken."]);
    exit;
}
$stmt->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Handle profile picture upload
    $upload_dir1 = 'uploads/profile_pictures/';
    $upload_dir2 = 'staff_side/uploads/profile_pictures/';
    $profile_picture_path = '';
    if ($profile_picture && $profile_picture['error'] == 0) {
        $file_ext = pathinfo($profile_picture['name'], PATHINFO_EXTENSION);
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($file_ext), $allowed_exts)) {
            $new_filename = uniqid('profile_', true) . '.' . $file_ext;
            $profile_picture_path = $upload_dir1 . $new_filename;
            move_uploaded_file($profile_picture['tmp_name'], $profile_picture_path);
            copy($profile_picture_path, $upload_dir2 . $new_filename);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid image format."]);
            exit;
        }
    }

    // Insert into staff_accounts table
    $stmt = $conn->prepare("INSERT INTO staff_accounts (employee_id, username, password, role, profile_picture) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $employee_id, $username, $hashed_password, $role, $profile_picture_path);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Employee account created successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error creating account."]);
    }
    $stmt->close();
    $conn->close();
}
?>
