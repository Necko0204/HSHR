<<<<<<< HEAD
<?php
session_name('admin_session');
session_start();

// Debug: Check if session is properly set
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
=======

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
>>>>>>> b4d7848b06ab22af04ad819b6adc005b916dbc9b
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Management - School</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="background.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: white;
            color: black;
            min-height: 100vh;
            margin: 0; /* Reset margin to ensure no unwanted space */
        }

        /* Wrapper for main content */
        .wrapper {
            margin-left: 270px; /* Sidebar width */
            padding: 20px;
            max-width: calc(100% - 270px); /* Adjust width to subtract sidebar width */
            transition: all 0.3s ease; /* Smooth transition */
        }

            /* Adjust wrapper when sidebar is hidden on small screens */
            @media (max-width: 768px) {
                .wrapper {
                    margin-left: 0;
                    max-width: 100%;
                    padding: 15px; /* Adjust padding for smaller screens */
                }
            }

            /* Content section styling */
            .content {
                margin-top: 20px; /* Ensure spacing between navbar and content */
            }

            h2 {
                font-size: 2rem;
                margin-bottom: 20px;
                color: #333;
            }

            /* Table and card styling */
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

            /* Form styling */
            form {
                background-color: white;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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

            .mb-3 {
                margin-bottom: 20px;
            }

            /* Adjust card elements */
            .card-body {
                padding: 15px;
            }

            /* Additional layout adjustments for smaller screens */
            @media (max-width: 768px) {
                .card {
                    padding: 15px;
                }
                .btn-primary {
                    padding: 8px 16px;
                }
                .form-control {
                    padding: 8px;
                }
            }
            
            </style>
        </head>
<body>

    <!-- Sidebar & Navbar-->
    <?php include 'sidebar.php'; ?>
    <?php include 'nav_header.php'; ?>

<<<<<<< HEAD
    <!-- Main content wrapper -->
    <main class="wrapper">
        <h2>Employee Details</h2>
            <!-- Personal Information -->
            <div class="card mb-3">
                <div class="card-header">
                    Personal Information
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td><label for="id">ID</label></td>
                            <td><input type="text" class="form-control" id="id" name="id" required></td>
                        </tr>
                        <tr>
                            <td><label for="lastname">Last Name</label></td>
                            <td><input type="text" class="form-control" id="lastname" name="lastname" required></td>
                        </tr>
                        <tr>
                            <td><label for="firstname">First Name</label></td>
                            <td><input type="text" class="form-control" id="firstname" name="firstname" required></td>
                        </tr>
                        <tr>
                            <td><label for="middlename">Middle Name</label></td>
                            <td><input type="text" class="form-control" id="middlename" name="middlename"></td>
                        </tr>
                        <tr>
                            <td><label for="suffix">Suffix</label></td>
                            <td><input type="text" class="form-control" id="suffix" name="suffix"></td>
                        </tr>
                        <tr>
                            <td><label for="gender">Gender</label></td>
                            <td>
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="othernamesused">Other Names Used</label></td>
                            <td><input type="text" class="form-control" id="othernamesused" name="othernamesused"></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Address & Marital Info -->
            <div class="card mb-3">
                <div class="card-header">
                    Address & Marital Information
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td><label for="homeaddress">Home Address</label></td>
                            <td><textarea class="form-control" id="homeaddress" name="homeaddress"></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="maritalstatus">Marital Status</label></td>
                            <td><input type="text" class="form-control" id="maritalstatus" name="maritalstatus"></td>
                        </tr>
                        <tr>
                            <td><label for="nameofspouse">Name of Spouse</label></td>
                            <td><input type="text" class="form-control" id="nameofspouse" name="nameofspouse"></td>
                        </tr>
                        <tr>
                            <td><label for="no_of_children">Number of Children</label></td>
                            <td><input type="number" class="form-control" id="no_of_children" name="no_of_children"></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Physical Attributes -->
            <div class="card mb-3">
                <div class="card-header">
                    Physical Attributes
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td><label for="height">Height</label></td>
                            <td><input type="text" class="form-control" id="height" name="height"></td>
                        </tr>
                        <tr>
                            <td><label for="weight">Weight</label></td>
                            <td><input type="text" class="form-control" id="weight" name="weight"></td>
                        </tr>
                        <tr>
                            <td><label for="colorofeyes">Color of Eyes</label></td>
                            <td><input type="text" class="form-control" id="colorofeyes" name="colorofeyes"></td>
                        </tr>
                        <tr>
                            <td><label for="colorofhair">Color of Hair</label></td>
                            <td><input type="text" class="form-control" id="colorofhair" name="colorofhair"></td>
                        </tr>
                        <tr>
                            <td><label for="scars_marks_distinguishingfeatures">Scars/Marks</label></td>
                            <td><input type="text" class="form-control" id="scars_marks_distinguishingfeatures" name="scars_marks_distinguishingfeatures"></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- SSS and Other Identifications -->
            <div class="card mb-3">
                <div class="card-header">
                    SSS and Other Identifications
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td><label for="sss_gsisno">SSS/GSIS No.</label></td>
                            <td><input type="text" class="form-control" id="sss_gsisno" name="sss_gsisno"></td>
                        </tr>
                        <tr>
                            <td><label for="dateofbirth">Date of Birth</label></td>
                            <td><input type="date" class="form-control" id="dateofbirth" name="dateofbirth" required></td>
                        </tr>
                        <tr>
                            <td><label for="age">Age</label></td>
                            <td><input type="number" class="form-control" id="age" name="age"></td>
                        </tr>
                        <tr>
                            <td><label for="placeofbirth">Place of Birth</label></td>
                            <td><input type="text" class="form-control" id="placeofbirth" name="placeofbirth"></td>
                        </tr>
                        <tr>
                            <td><label for="citizenship">Citizenship</label></td>
                            <td><input type="text" class="form-control" id="citizenship" name="citizenship"></td>
                        </tr>
                        <tr>
                            <td><label for="provinceoforigin">Province of Origin</label></td>
                            <td><input type="text" class="form-control" id="provinceoforigin" name="provinceoforigin"></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Religion & Blood Type -->
            <div class="card mb-3">
                <div class="card-header">
                    Religion & Blood Type
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td><label for="religion">Religion</label></td>
                            <td><input type="text" class="form-control" id="religion" name="religion"></td>
                        </tr>
                        <tr>
                            <td><label for="bloodtype">Blood Type</label></td>
                            <td><input type="text" class="form-control" id="bloodtype" name="bloodtype"></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card mb-3">
                <div class="card-header">
                    Contact Information
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td><label for="tel_no_home">Home Tel. No.</label></td>
                            <td><input type="text" class="form-control" id="tel_no_home" name="tel_no_home"></td>
                        </tr>
                        <tr>
                            <td><label for="businessphonenumber">Business Phone</label></td>
                            <td><input type="text" class="form-control" id="businessphonenumber" name="businessphonenumber"></td>
                        </tr>
                        <tr>
                            <td><label for="mobilephone">Mobile Phone</label></td>
                            <td><input type="text" class="form-control" id="mobilephone" name="mobilephone"></td>
                        </tr>
                        <tr>
                            <td><label for="email1">Email 1</label></td>
                            <td><input type="email" class="form-control" id="email1" name="email1"></td>
                        </tr>
                        <tr>
                            <td><label for="email2">Email 2</label></td>
                            <td><input type="email" class="form-control" id="email2" name="email2"></td>
                        </tr>
                        <tr>
                            <td><label for="faxno">Fax Number</label></td>
                            <td><input type="text" class="form-control" id="faxno" name="faxno"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        </main>
        <!-- Bootstrap JS -->
        <script src="background.js"></script>
=======
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
>>>>>>> b4d7848b06ab22af04ad819b6adc005b916dbc9b
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>