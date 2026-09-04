<?php
require_once __DIR__ . '/database_connection.php';
// Function to get user data from the database
function getUserData($admin_id) {
    // Database connection
    $conn = createDatabaseConnection();

    // Prepare and bind
    $stmt = $conn->prepare(
        "SELECT auto_id, id, name, email, position, username, profile_picture,
                age, bio, phone, address, experiences, sidebarOn, darkmodeOn
         FROM admin WHERE id = ?"
    );
    $stmt->bind_param("s", $admin_id);

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
    $conn = createDatabaseConnection();

    // Query to fetch employees sorted by numeric ID
    $sql = "SELECT id, lastname, firstname, gender, email1, status FROM employees
            ORDER BY CAST(SUBSTRING(id, 9) AS UNSIGNED)";

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


// Function to get employee details by ID
function getEmployeeDetails($id) {
    // Database connection
    $conn = createDatabaseConnection();

    if (!$id) {
        return null;
    }

    // Prepare the query to prevent SQL injection
    $stmt = $conn->prepare("
        SELECT employees.*, ed_2ndhalf.*
        FROM employees
        LEFT JOIN ed_2ndhalf ON employees.id = ed_2ndhalf.employee_id
        WHERE employees.id = ?
    ");

    $stmt->bind_param("s", $id); // Assuming 'id' is a string, if it's an integer use "i"
    $stmt->execute();
    $result = $stmt->get_result();

    $row = ($result->num_rows > 0) ? $result->fetch_assoc() : null;

    // Close connections
    $stmt->close();
    $conn->close();

    return $row;
}
function getStaffAccounts($conn) {
    $sql = "SELECT staff_accounts.id, staff_accounts.employee_id, staff_accounts.username,
                   staff_accounts.profile_picture, staff_accounts.role, staff_accounts.status,
                   employees.firstname, employees.lastname, employees.status AS employee_status
            FROM staff_accounts
            LEFT JOIN employees ON staff_accounts.employee_id = employees.id
            WHERE LOWER(staff_accounts.status) = 'active'
            ORDER BY CAST(SUBSTRING(staff_accounts.employee_id, 9) AS UNSIGNED)";

    $result = mysqli_query($conn, $sql);

    $staffAccounts = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $staffAccounts[] = $row;
        }
    }

    return $staffAccounts;
}


// Fetch user data
$userData = isset($_SESSION['admin_id'])
    ? getUserData($_SESSION['admin_id'])
    : null;
?>
