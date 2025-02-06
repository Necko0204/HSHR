
<?php
require 'db_config.php'; // Ensure this file contains your MySQLi connection ($conn)

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the query to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->bind_param("s", $id); // Assuming 'id' is a string, if it's an integer use "i"
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        $row = null;
    }

    $stmt->close();
    $conn->close();
} else {
    $row = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* General page styling */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f4f7fc;
}

/* Sidebar styling */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 250px;
    background-color: #333;
    color: #fff;
    padding-top: 20px;
    padding-left: 20px;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
}

.sidebar a {
    color: white;
    display: block;
    text-decoration: none;
    padding: 10px 0;
}

.sidebar a:hover {
    background-color: #575757;
}

/* Content wrapper for employee details */
.container {
    margin-left: 270px;
    padding: 20px;
}

/* Form wrapper card styling */
form {
    background-color: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    font-size: 2rem;
    margin-bottom: 20px;
    color: #333;
}

/* Card styling for each section */
.mb-3 {
    margin-bottom: 20px;
}

.card {
    border: 1px solid #ddd;
    border-radius: 10px;
    margin-bottom: 20px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    background-color: #fff;
}

.card-header {
    font-weight: bold;
    font-size: 1.2rem;
    background-color: #f8f9fa;
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.card-body {
    padding: 20px 0;
}

.card-body .form-label {
    font-weight: 500;
    color: #333;
}

.form-control {
    border-radius: 8px;
    border: 1px solid #ddd;
    padding: 10px;
    margin-bottom: 15px;
}

.form-control:focus {
    border-color: #6c63ff;
    box-shadow: 0 0 5px rgba(108, 99, 255, 0.2);
}

/* Button styling */
.btn-primary {
    background-color: #6c63ff;
    border-color: #6c63ff;
    padding: 10px 20px;
    border-radius: 8px;
}

.btn-primary:hover {
    background-color: #5748d0;
    border-color: #5748d0;
}

/* Adjusting layout of form elements for better spacing */
.mb-3 {
    width: 100%;
}

@media (max-width: 768px) {
    .container {
        margin-left: 0;
    }
    
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
        padding: 10px;
    }
}

        /* Ensure the sidebar takes up appropriate space */
        .sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background-color: #343a40;
            padding-top: 20px;
            color: white;
        }

        .sidebar a {
            color: white;
            padding: 10px 15px;
            display: block;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #575757;
        }

        /* Container for the form and sidebar */
        .content-wrapper {
            margin-left: 250px; /* This will push the content beside the sidebar */
            padding: 20px;
        }

        /* Optional: Add a little spacing to the form */
        .form-container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
        }

        /* Adjust the form fields to be aligned nicely */
        .form-container .form-label {
            font-weight: 600;
        }

        .form-container .form-control {
            border-radius: 8px;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

<!-- Main content wrapper -->
<div class="container mt-5">
    <h2>Employee Details</h2>
    
    <!-- Personal Information -->
    <div class="card mb-3">
        <div class="card-header">Personal Information</div>
        <div class="card-body">
            <table class="table">
                <tr>
                    <td><label for="id">ID</label></td>
                    <td><span id="id"><?= $row['id'] ?? ''?></td>
                </tr>
                <tr>
                    <td><label for="lastname">Last Name</label></td>
                    <td><span id="lastname"><?= $row['lastname'] ?? 'N/A' ?></span></td>
                </tr>
                <tr>
                    <td><label for="firstname">First Name</label></td>
                    <td><span id="firstname"><?= $row['firstname'] ?? 'N/A' ?></span></td>
                </tr>
                <tr>
        <td><label for="middlename">Middle Name</label></td>
        <td><span id="middlename"><?= $row['middlename'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="suffix">Suffix</label></td>
        <td><span id="suffix"><?= $row['suffix'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="gender">Gender</label></td>
        <td><span id="gender"><?= $row['gender'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="homeaddress">Home Address</label></td>
        <td><span id="homeaddress"><?= $row['homeaddress'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="maritalstatus">Marital Status</label></td>
        <td><span id="maritalstatus"><?= $row['maritalstatus'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="nameofspouse">Name of Spouse</label></td>
        <td><span id="nameofspouse"><?= $row['nameofspouse'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="no_of_children">Number of Children</label></td>
        <td><span id="no_of_children"><?= $row['no_of_children'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="height">Height(cm)</label></td>
        <td><span id="height"><?= $row['height(cm)'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="weight">Weight(kg)</label></td>
        <td><span id="weight"><?= $row['weight(kg)'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="eyecolor">Eye Color</label></td>
        <td><span id="eyecolor"><?= $row['eyecolor'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="haircolor">Hair Color</label></td>
        <td><span id="haircolor"><?= $row['haircolor'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="distinguishingfeatures">Distinguishing Features</label></td>
        <td><span id="distinguishingfeatures"><?= $row['distinguishingfeatures'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="sss_gsis">SSS/GSIS No</label></td>
        <td><span id="sss_gsis"><?= $row['sss_gsis'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="dateofbirth">Date of Birth</label></td>
        <td><span id="dateofbirth"><?= $row['dateofbirth'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="placeofbirth">Place of Birth</label></td>
        <td><span id="placeofbirth"><?= $row['placeofbirth'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="citizenship">Citizenship</label></td>
        <td><span id="citizenship"><?= $row['citizenship'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="provinceoforigin">Province of Origin</label></td>
        <td><span id="provinceoforigin"><?= $row['provinceoforigin'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="religion">Religion</label></td>
        <td><span id="religion"><?= $row['religion'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="bloodtype">Blood Type</label></td>
        <td><span id="bloodtype"><?= $row['bloodtype'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="homephone">Home Phone</label></td>
        <td><span id="homephone"><?= $row['homephone'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="businessphone">Business Phone</label></td>
        <td><span id="businessphone"><?= $row['businessphone'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="mobilephone">Mobile Phone</label></td>
        <td><span id="mobilephone"><?= $row['mobilephone'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="email1">Email 1</label></td>
        <td><span id="email1"><?= $row['email1'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="email2">Email 2</label></td>
        <td><span id="email2"><?= $row['email2'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="faxno">Fax No</label></td>
        <td><span id="faxno"><?= $row['faxno'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
        <td><label for="workoccupation">Work Occupation</label></td>
        <td><span id="workoccupation"><?= $row['workoccupation'] ?? 'N/A' ?></span></td>
    </tr>
    <tr>
    <td><label for="passportno">Passport No</label></td>
    <td><span id="passportno"><?= $row['passportno'] ?? 'N/A' ?></span></td>
</tr>
<tr>
    <td><label for="expirydate">Expiry Date</label></td>
    <td><span id="expirydate"><?= $row['expirydate'] ?? 'N/A' ?></span></td>
</tr>
<tr>
    <td><label for="typeofvisa">Type of Visa</label></td>
    <td><span id="typeofvisa"><?= $row['typeofvisa'] ?? 'N/A' ?></span></td>
</tr>
<tr>
    <td><label for="tinno">TIN No</label></td>
    <td><span id="tinno"><?= $row['tinno'] ?? 'N/A' ?></span></td>
</tr>
<tr>
    <td><label for="datejoined">Date Joined</label></td>
    <td><span id="datejoined"><?= $row['datejoined'] ?? 'N/A' ?></span></td>
</tr>
<tr>
    <td><label for="fbvibername">FB/Viber Name</label></td>
    <td><span id="fbvibername"><?= $row['fbvibername'] ?? 'N/A' ?></span></td>
</tr>
<tr>
    <td><label for="instagram">Instagram</label></td>
    <td><span id="instagram"><?= $row['instagram'] ?? 'N/A' ?></span></td>
</tr>
<tr>
    <td><label for="kidsnames">Kids Names</label></td>
    <td><span id="kidsnames"><?= $row['kidsnames'] ?? 'N/A' ?></span></td>
</tr>

            </table>
        </div>
    </div>
</div>



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

