<?php
require 'db_config.php'; // Database connection

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => '',
    'data' => null
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Convert empty fields to NULL to prevent binding errors
    foreach ($_POST as $key => $value) {
        $_POST[$key] = (!empty($value) || $value === "0") ? $value : null;
    }

    // Check for undefined keys and set default values if necessary
    $nature_of_business = $_POST['nature_of_business'] ?? null;
    $nbi_clearance = $_FILES['nbi_clearance'] ?? null;
    $police_clearance = $_FILES['police_clearance'] ?? null;
    $barangay_clearance = $_FILES['barangay_clearance'] ?? null;
    $orientation_certificate = $_FILES['orientation_certificate'] ?? null;

    // Prepare the stored procedure call for inserting into the employees table
    $stmt = $conn->prepare("CALL insert_employees(
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @employee_id
    )");

    if (!$stmt) {
        $response['message'] = "Error in preparing statement: " . $conn->error;
        echo json_encode($response);
        exit;
    }

    // Bind parameters to insert into the employees table
    $stmt->bind_param(
        "ssssssssssiiissssissssssssssssssssssss",
        $_POST['lastname'], $_POST['firstname'], $_POST['middlename'], $_POST['suffix'], $_POST['gender'], 
        $_POST['othernamesused'], $_POST['homeaddress'], $_POST['maritalstatus'], $_POST['nameofspouse'], 
        $_POST['no_of_children'], $_POST['height'], $_POST['weight'], $_POST['colorofeyes'], $_POST['colorofhair'], 
        $_POST['scars_marks_distinguishingfeatures'], $_POST['sss_gsisno'], $_POST['dateofbirth'], $_POST['age'], 
        $_POST['placeofbirth'], $_POST['citizenship'], $_POST['provinceoforigin'], $_POST['religion'], $_POST['bloodtype'], 
        $_POST['tel_no_home'], $_POST['businessphonenumber'], $_POST['mobilephone'], $_POST['email1'], $_POST['email2'], 
        $_POST['faxno'], $_POST['work_occupation'], $_POST['passportno'], $_POST['expirydate'], $_POST['typeofvisaissued'], 
        $_POST['tinno'], $_POST['datejoiningindsclc'], $_POST['fb_messenger_vibername'], $_POST['instagramname'], 
        $_POST['kids_children_names']
    );

    $conn->begin_transaction();
    try {
        // Execute stored procedure for inserting into employees table
        if (!$stmt->execute()) {
            throw new Exception("Error executing statement: " . $stmt->error);
        }

        // Retrieve employee_id from stored procedure
        $result = $conn->query("SELECT @employee_id AS employee_id");
        $row = $result->fetch_assoc();
        $employee_id = $row['employee_id'] ?? null;

        if (!$employee_id) {
            throw new Exception("Error: Failed to retrieve employee ID.");
        }

        // Verify employee_id exists in employees table
        $check_employee = $conn->prepare("SELECT id FROM employees WHERE id = ?");
        $check_employee->bind_param("s", $employee_id);
        $check_employee->execute();
        $check_employee->store_result();

        if ($check_employee->num_rows === 0) {
            throw new Exception("Error: Employee ID does not exist in the employee table.");
        }

        // Education data
        $schoolnames = $_POST['schoolname'];
        $degreeobtained = $_POST['degreeobtained'];
        $inclusivedates = $_POST['inclusivedates'];
        $yearobtained = $_POST['yearobtained'];
        
        // Work Experience data
        $companies = $_POST['company'];
        $nature_of_business = $_POST['nature_of_business'] ?? []; // Ensure it's an array
        $designations = $_POST['designation'];
        $position_period_assumed = $_POST['position_period_assumed'];
        $nature_of_office = $_POST['nature_of_office'];
        
        // Additional Data
        $professional_licenses = $_POST['professional_licenses'];
        $special_trainings = $_POST['special_trainings'];
        $special_interests_skills = $_POST['special_interests_skills'];
        
        // Emergency Contact
        $emergency_contact_last_name = $_POST['last_name'];
        $emergency_contact_first_name = $_POST['first_name'];
        $emergency_contact_middle_initial = $_POST['middle_initial'];
        $emergency_contact_suffix = $_POST['suffix'];
        $emergency_contact_relationship = $_POST['relationship'];
        $emergency_contact_address = $_POST['address'];
        $emergency_contact_tel_home = $_POST['tel_home'];
        $emergency_contact_tel_business = $_POST['tel_business'];
        $emergency_contact_mobile_phone = $_POST['mobile_phone'];
        
        // Organization Memberships
        $organizations = $_POST['organization'];
        $places = $_POST['place'];
        $dates_of_membership = $_POST['date_of_membership'];
        $positions_held = $_POST['position_held'];
        
        // File Uploads
        function uploadFile($file, $destination_folder) {
            if (!empty($file['name'])) {
                $filename = time() . "_" . basename($file["name"]);
                $target_file = $destination_folder . $filename;
                if (move_uploaded_file($file["tmp_name"], $target_file)) {
                    return $target_file;
                }
            }
            return null;
        }
        
        $nbi_clearance_path = uploadFile($nbi_clearance, "uploads/");
        $police_clearance_path = uploadFile($police_clearance, "uploads/");
        $barangay_clearance_path = uploadFile($barangay_clearance, "uploads/");
        $orientation_certificate_path = uploadFile($orientation_certificate, "uploads/");
        
        // Prepare and execute statement
        $stmt2 = $conn->prepare("INSERT INTO ed_2ndhalf 
            (employee_id, schoolname, degreeobtained, inclusivedates, yearobtained, 
            company, nature_of_business, designation, position_period_assumed, nature_of_office,
            professional_licenses, special_trainings, special_interests_skills,
            emergency_contact_last_name, emergency_contact_first_name, emergency_contact_middle_initial,
            emergency_contact_suffix, emergency_contact_relationship, emergency_contact_address,
            emergency_contact_tel_home, emergency_contact_tel_business, emergency_contact_mobile_phone,
            organization, place, date_of_membership, position_held,
            nbi_clearance_path, police_clearance_path, barangay_clearance_path, orientation_certificate_path) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if (!$stmt2) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }
        
        foreach ($schoolnames as $index => $school) {
            $degree_obtained = $degreeobtained[$index] ?? null;
            $inclusive_dates = $inclusivedates[$index] ?? null;
            $year_obtained = $yearobtained[$index] ?? null;
            $company = $companies[$index] ?? null;
            $nature_of_business = $nature_of_business[$index] ?? null;
            $designation = $designations[$index] ?? null;
            $position_period = $position_period_assumed[$index] ?? null;
            $nature_office = $nature_of_office[$index] ?? null;
            $professional_license = $professional_licenses[$index] ?? null;
            $special_training = $special_trainings[$index] ?? null;
            $special_interest_skill = $special_interests_skills[$index] ?? null;
            $organization = $organizations[$index] ?? null;
            $place = $places[$index] ?? null;
            $date_of_membership = $dates_of_membership[$index] ?? null;
            $position_held = $positions_held[$index] ?? null;

            $stmt2->bind_param("ssssssssssssssssssssssssssssss",
                $employee_id, $school, $degree_obtained, $inclusive_dates, $year_obtained,
                $company, $nature_of_business, $designation, 
                $position_period, $nature_office,
                $professional_license, $special_training, $special_interest_skill,
                $emergency_contact_last_name, $emergency_contact_first_name, $emergency_contact_middle_initial,
                $emergency_contact_suffix, $emergency_contact_relationship, $emergency_contact_address,
                $emergency_contact_tel_home, $emergency_contact_tel_business, $emergency_contact_mobile_phone,
                $organization, $place, $date_of_membership, $position_held,
                $nbi_clearance_path, $police_clearance_path, $barangay_clearance_path, $orientation_certificate_path
            );
            
            if (!$stmt2->execute()) {
                throw new Exception("Error executing statement: " . $stmt2->error);
            }
        }
        
        $response['success'] = true;
        $response['message'] = "Data inserted successfully.";
        $response['data'] = ['employee_id' => $employee_id];
        
        $stmt2->close();
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        $response['message'] = "Transaction failed: " . $e->getMessage();
    } finally {
        $stmt->close();
        $conn->close();
    }
} else {
    $response['message'] = "Invalid request method.";
}

echo json_encode($response);
?>