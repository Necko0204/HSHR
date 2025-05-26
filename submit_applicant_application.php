<?php
session_start();
require_once 'db_config.php'; // Database connection file

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $conn->autocommit(FALSE); // Start transaction

        // Generate the next applicant ID (APP-HSHI20250001 format)
        $year = date('Y');
        $prefix = "APP-HSHI{$year}";
        
        // Fetch the last ID and increment it
        $query = "SELECT applicant_id FROM applicants WHERE applicant_id LIKE '$prefix%' ORDER BY applicant_id DESC LIMIT 1";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $last_id = intval(substr($row['applicant_id'], -5)); // Extract last 5 digits
            $new_id = $prefix . str_pad($last_id + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $new_id = "{$prefix}00001";
        }

        // Sanitize and fetch form inputs
        $lastname = htmlspecialchars(trim($_POST['lastname']));
        $firstname = htmlspecialchars(trim($_POST['firstname']));
        $middlename = htmlspecialchars(trim($_POST['middlename'] ?? ''));
        $email = htmlspecialchars(trim($_POST['email']));
        $gender = $_POST['gender'];
        $dateofbirth = $_POST['dateofbirth'];
        $contact = htmlspecialchars(trim($_POST['contact']));
        
        $highest_degree = htmlspecialchars(trim($_POST['highest_degree']));
        $university = htmlspecialchars(trim($_POST['university']));
        $graduation_year = intval($_POST['graduation_year']);
        $major = htmlspecialchars(trim($_POST['major'] ?? ''));
        
        $previous_school = htmlspecialchars(trim($_POST['previous_school']));
        $years_of_experience = intval($_POST['years_of_experience']);
        $subjects_taught = htmlspecialchars(trim($_POST['subjects_taught'] ?? ''));
        
        $license = htmlspecialchars(trim($_POST['license']));
        $certifications = htmlspecialchars(trim($_POST['certifications'] ?? ''));
        
        // Handle file upload
        $resume = $_FILES['resume'];
        $allowed_extensions = ['pdf', 'doc', 'docx'];
        $upload_dir = '../applicants_uploads/';
        
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_ext = pathinfo($resume['name'], PATHINFO_EXTENSION);
        if (!in_array(strtolower($file_ext), $allowed_extensions)) {
            throw new Exception("Invalid file format. Only PDF, DOC, and DOCX allowed.");
        }
        
        $resume_filename = time() . '_' . basename($resume['name']);
        $resume_path = "{$upload_dir}{$resume_filename}";
        
        if (!move_uploaded_file($resume['tmp_name'], $resume_path)) {
            throw new Exception("Failed to upload resume. Try again.");
        }
        
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO applicants (applicant_id, lastname, firstname, middlename, email, gender, dateofbirth, contact, highest_degree, university, graduation_year, major, previous_school, years_of_experience, subjects_taught, license, certifications, resume_path, submitted_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssssssississssss", $new_id, $lastname, $firstname, $middlename, $email, $gender, $dateofbirth, $contact, $highest_degree, $university, $graduation_year, $major, $previous_school, $years_of_experience, $subjects_taught, $license, $certifications, $resume_path);
        $stmt->execute();
        
        $conn->commit(); // Commit transaction
        $conn->autocommit(TRUE); // Enable auto-commit again

        echo json_encode(['status' => 'success', 'message' => 'Application submitted successfully.']);

    } catch (Exception $e) {
        $conn->rollback(); // Rollback transaction
        $conn->autocommit(TRUE); // Enable auto-commit again
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>