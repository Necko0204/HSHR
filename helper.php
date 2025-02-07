<?php
// Function to get user data from the database
function getUserData($admin_id) {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'humanresource');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("SELECT * FROM admin WHERE id = ?");
    $stmt->bind_param("i", $admin_id);

    // Execute and fetch
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if the user is found
    if ($result->num_rows > 0) {
        // Fetch the data as an associative array
        $userData = $result->fetch_assoc();
    } else {
        // If no data found
        $userData = null;
    }

    // Close connections
    $stmt->close();
    $conn->close();

    return $userData; // Return the entire user data as an associative array
}

// Function to get all employees from the database
function getEmployees() {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'humanresource');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to fetch employees
    $sql = "SELECT id, lastname, firstname, gender, email1, status FROM employees";
    $result = $conn->query($sql);

    $employees = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $employees[] = $row;
        }
    }

    // Close connection
    $conn->close();

    return $employees;
}

// Fetch user data
$userData = getUserData($_SESSION['admin_id']);

?>
