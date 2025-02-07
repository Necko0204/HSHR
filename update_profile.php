<?php
session_name('admin_session');
session_start();

// Include database connection
include 'db_config.php';

// Function to handle profile picture upload
function handleProfilePictureUpload($file) {
    $target_dir = "uploads/profile_pictures/";
    $target_file = $target_dir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is an actual image
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        return ['error' => 'File is not an image.'];
    }

    // Check file size (limit to 5MB)
    if ($file["size"] > 5000000) {
        return ['error' => 'Sorry, your file is too large.'];
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        return ['error' => 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.'];
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ['success' => $target_file]; // Return the file path
    } else {
        return ['error' => 'Sorry, there was an error uploading your file.'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user ID
    $id = $_POST['id'];

    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    $username = $_POST['username'];
    $age = $_POST['age'];  // Age
    $bio = $_POST['bio'];  // Bio

    // Fetch current profile picture
    $sql = "SELECT profile_picture FROM admin WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($currentProfilePicture);
    $stmt->fetch();
    $stmt->close();

    // Handle profile picture upload (only if a new file is uploaded)
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $uploadResult = handleProfilePictureUpload($_FILES['profile_picture']);
        if (isset($uploadResult['error'])) {
            echo $uploadResult['error'];
            exit;
        }
        $profile_picture = $uploadResult['success']; // New uploaded file
    } else {
        $profile_picture = $currentProfilePicture; // Keep existing profile picture
    }

    // Update user data in the database
    $sql = "UPDATE admin SET name=?, email=?, position=?, username=?, profile_picture=?, age=?, bio=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo "Error preparing the query: " . $conn->error;
        exit;
    }

    $stmt->bind_param("sssssssi", $name, $email, $position, $username, $profile_picture, $age, $bio, $id);

    if ($stmt->execute()) {
        echo "Profile updated successfully.";
    } else {
        echo "Error updating profile: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
