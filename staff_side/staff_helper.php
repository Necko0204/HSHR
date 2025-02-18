<?php
$staff_id = $_SESSION['employee_id']; // Get employee ID from session

// Function to get staff data from the database
function getStaffData($staff_id) {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'humanresource');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("
        SELECT sa.*, e.*
        FROM staff_accounts sa
        JOIN employees e ON sa.employee_id = e.id
        WHERE sa.employee_id = ?
    ");
    $stmt->bind_param("s", $staff_id); // "s" because employee_id is a string

    // Execute and fetch
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if the user is found
    if ($result->num_rows > 0) {
        $staffData = $result->fetch_assoc();
    } else {
        $staffData = null;
    }

    // Close connections
    $stmt->close();
    $conn->close();

    return $staffData; 
}

// Fetch staff data
$staffData = getStaffData($staff_id);
?>
