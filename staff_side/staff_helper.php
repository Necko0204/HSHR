<?php
// Function to get staff data from the database, including employee name
function getStaffData($staff_id) {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'humanresource');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind: JOIN staff_accounts with employees to fetch firstname & lastname
    $stmt = $conn->prepare("
        SELECT sa.*, e.firstname, e.lastname 
        FROM staff_accounts sa
        JOIN employees e ON sa.employee_id = e.id
        WHERE sa.employee_id = ?
    ");
    $stmt->bind_param("i", $staff_id);

    // Execute and fetch
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if the user is found
    if ($result->num_rows > 0) {
        // Fetch the data as an associative array
        $staffData = $result->fetch_assoc();
    } else {
        // If no data found
        $staffData = null;
    }

    // Close connections
    $stmt->close();
    $conn->close();

    return $staffData; // Return the entire staff data as an associative array
}
?>
