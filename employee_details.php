<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_name('admin_session');
session_start();
include 'includes/breadcrumb.php';
require 'db_config.php'; // Ensure this file contains your MySQLi connection ($conn)
include 'helper.php';


// Employee Detail - Fetch employee details from the database
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute the query to fetch employee details
    $stmt = $conn->prepare("SELECT employees.*, ed_2ndhalf.* 
                            FROM employees
                            LEFT JOIN ed_2ndhalf ON employees.id = ed_2ndhalf.employee_id 
                            WHERE employees.id = ?");
    $stmt->bind_param("s", $id); // Use "s" for string parameter, use "i" for integer if needed
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $employeeDetails = $result->fetch_assoc();
    } else {
        $employeeDetails = null; // No records found
    }

    $stmt->close();
} else {
    $employeeDetails = null; // No ID provided
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/asdasdasd123123123123123.jpg">
    <title>Holy Spirit Human Resource</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="background.css">
</head>
<body>

<!-- Sidebar & Navbar in a separate container -->
<div class="main-container">
    <?php include 'sidebar.php'; ?>
    </div>
    <div class="content-container">
        <?php include 'nav_header.php'; ?>
        </div>


        <!-- Main content wrapper -->
        <main class="wrapper">
                <?php
                echo generateBreadcrumb();
                ?>
            <div class="d-flex justify-content-start align-items-center mb-4">
            <h2 class="fw-bold text-dark mb-0">Employee Detailed View</h2>
            </div>
        
            <!-- Personal Information -->
            <div class="card mb-3">
            <div class="card-header2">Personal Information</div>
            <div class="card-body2">
                <div class="table-responsive">
                <table class="table table-borderless table-hover align-middle">
                    <tr>
                    <td><label for="id">ID</label></td>
                    <td><span id="id"><?= $employeeDetails['employee_id'] ?? 'N/A' ?></span></td>
                    </tr>
                      <tr>
                    <td><label for="lastname">Salary</label></td>
                    <td><span id="lastname"><?= $employeeDetails['salary'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="lastname">Last Name</label></td>
                    <td><span id="lastname"><?= $employeeDetails['lastname'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="firstname">First Name</label></td>
                    <td><span id="firstname"><?= $employeeDetails['firstname'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="middlename">Middle Name</label></td>
                    <td><span id="middlename"><?= $employeeDetails['middlename'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="suffix">Suffix</label></td>
                    <td><span id="suffix"><?= $employeeDetails['suffix'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="gender">Gender</label></td>
                    <td><span id="gender"><?= $employeeDetails['gender'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="homeaddress">Home Address</label></td>
                    <td><span id="homeaddress"><?= $employeeDetails['homeaddress'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="maritalstatus">Marital Status</label></td>
                    <td><span id="maritalstatus"><?= $employeeDetails['maritalstatus'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="nameofspouse">Name of Spouse</label></td>
                    <td><span id="nameofspouse"><?= $employeeDetails['nameofspouse'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="no_of_children">Number of Children</label></td>
                    <td><span id="no_of_children"><?= $employeeDetails['no_of_children'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="height">Height (cm)</label></td>
                    <td><span id="height"><?= $employeeDetails['height'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="weight">Weight (kg)</label></td>
                    <td><span id="weight"><?= $employeeDetails['weight'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="eyecolor">Eye Color</label></td>
                    <td><span id="eyecolor"><?= $employeeDetails['colorofeyes'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="haircolor">Hair Color</label></td>
                    <td><span id="haircolor"><?= $employeeDetails['colorofhair'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="distinguishingfeatures">Distinguishing Features</label></td>
                    <td><span id="distinguishingfeatures"><?= $employeeDetails['scars_marks_distinguishingfeatures'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="dateofbirth">Date of Birth</label></td>
                    <td><span id="dateofbirth"><?= $employeeDetails['dateofbirth'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="age">Age</label></td>
                    <td><span id="age"><?= $employeeDetails['age'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="placeofbirth">Place of Birth</label></td>
                    <td><span id="placeofbirth"><?= $employeeDetails['placeofbirth'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="citizenship">Citizenship</label></td>
                    <td><span id="citizenship"><?= $employeeDetails['citizenship'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="provinceoforigin">Province of Origin</label></td>
                    <td><span id="provinceoforigin"><?= $employeeDetails['provinceoforigin'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="religion">Religion</label></td>
                    <td><span id="religion"><?= $employeeDetails['religion'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="bloodtype">Blood Type</label></td>
                    <td><span id="bloodtype"><?= $employeeDetails['bloodtype'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="workoccupation">Work Occupation</label></td>
                    <td><span id="workoccupation"><?= $employeeDetails['workoccupation'] ?? 'N/A' ?></span></td>
                    </tr>
                </table>
                </div>
            </div>
            </div>

            <!-- Contact Information -->
            <div class="card mb-3">
            <div class="card-header2">Contact Information</div>
            <div class="card-body2">
                <div class="table-responsive">
                <table class="table">
                    <tr>
                    <td><label for="homephone">Home Phone</label></td>
                    <td><span id="homephone"><?= $employeeDetails['homephone'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="businessphone">Business Phone</label></td>
                    <td><span id="businessphone"><?= $employeeDetails['businessphone'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="mobilephone">Mobile Phone</label></td>
                    <td><span id="mobilephone"><?= $employeeDetails['mobilephone'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="email1">Email 1</label></td>
                    <td><span id="email1"><?= $employeeDetails['email1'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="email2">Email 2</label></td>
                    <td><span id="email2"><?= $employeeDetails['email2'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="faxno">Fax No</label></td>
                    <td><span id="faxno"><?= $employeeDetails['faxno'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="fbvibername">FB/Viber Name</label></td>
                    <td><span id="fbvibername"><?= $employeeDetails['fbvibername'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="instagram">Instagram</label></td>
                    <td><span id="instagram"><?= $employeeDetails['instagram'] ?? 'N/A' ?></span></td>
                    </tr>
                </table>
                </div>
            </div>
            </div>

            <!-- Government and Legal Documents -->
            <div class="card mb-4">
            <div class="card-header2">Government and Legal Documents</div>
            <div class="card-body2">
                <div class="table-responsive">
                <table class="table">
                    <tr>
                        <td><label for="sss_gsis">SSS/GSIS No</label></td>
                        <td><span id="sss_gsis"><?= $employeeDetails['sss_gsis'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                        <td><label for="passportno">Passport No</label></td>
                        <td><span id="passportno"><?= $employeeDetails['passportno'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="expirydate">Expiry Date</label></td>
                    <td><span id="expirydate"><?= $employeeDetails['expirydate'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="typeofvisa">Type of Visa</label></td>
                    <td><span id="typeofvisa"><?= $employeeDetails['typeofvisa'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="tinno">TIN No</label></td>
                    <td><span id="tinno"><?= $employeeDetails['tinno'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="datejoined">Date Joined</label></td>
                    <td><span id="datejoined"><?= $employeeDetails['datejoined'] ?? 'N/A' ?></span></td>
                    </tr>
                </table>
                </div>
            </div>
            </div>

            <!-- Educational Background -->
            <div class="card mb-3">
            <div class="card-header2">Educational Background</div>
            <div class="card-body2">
                <div class="table-responsive">
                <table class="table">
                    <tr>
                    <td><label for="yearobtained">Year Obtained</label></td>
                    <td><span id="yearobtained"><?= $employeeDetails['yearobtained'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="degreeobtained">Degree Obtained</label></td>
                    <td><span id="degreeobtained"><?= $employeeDetails['degreeobtained'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="inclusivedates">Inclusive Dates</label></td>
                    <td><span id="inclusivedates"><?= $employeeDetails['inclusivedates'] ?? 'N/A' ?></span></td>
                    </tr>
                </table>
                </div>
            </div>
            </div>

            <!-- Occupation Background -->
            <div class="card mb-3">
            <div class="card-header2">Employment/Occupational Background</div>
            <div class="card-body2">
                <div class="table-responsive">
                <table class="table">
                    <tr>
                    <td><label for="company">Company</label></td>
                    <td><span id="company"><?= $employeeDetails['company'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="nature_of_business">Nature of Business</label></td>
                    <td><span id="nature_of_business"><?= $employeeDetails['nature_of_business'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="designation">Designation</label></td>
                    <td><span id="designation"><?= $employeeDetails['designation'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="inclusive_dates">Inclusive Dates</label></td>
                    <td><span id="inclusive_dates"><?= $employeeDetails['inclusive_dates'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="position_period_assumed">Position/Period Assumed</label></td>
                    <td><span id="position_period_assumed"><?= $employeeDetails['position_period_assumed'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="nature_of_office">Nature of Office</label></td>
                    <td><span id="nature_of_office"><?= $employeeDetails['nature_of_office'] ?? 'N/A' ?></span></td>
                    </tr>
                </table>
                </div>
            </div>
            </div>

            <!-- Additional Data -->
            <div class="card mb-3">
            <div class="card-header2">Additional Data</div>
            <div class="card-body2">
                <div class="table-responsive">
                <table class="table">
                    <tr>
                    <td><label for="professional_licenses">Professional Licenses</label></td>
                    <td><span id="professional_licenses"><?= $employeeDetails['professional_licenses'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="special_trainings">Special Trainings</label></td>
                    <td><span id="special_trainings"><?= $employeeDetails['special_trainings'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="special_interests_skills">Special Interests/Skills</label></td>
                    <td><span id="special_interests_skills"><?= $employeeDetails['special_interests_skills'] ?? 'N/A' ?></span></td>
                    </tr>
                </table>
                </div>
            </div>
            </div>

            <!-- Emergency Contact -->
            <div class="card mb-3">
            <div class="card-header2">Emergency Contact</div>
            <div class="card-body2">
                <div class="table-responsive">
                <table class="table">
                    <tr>
                    <td><label for="last_name">Last Name</label></td>
                    <td><span id="last_name"><?= $employeeDetails['emergency_contact_last_name'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="first_name">First Name</label></td>
                    <td><span id="first_name"><?= $employeeDetails['emergency_contact_first_name'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="mi">Middle Initial</label></td>
                    <td><span id="mi"><?= $employeeDetails['emergency_contact_middle_initial'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="suffix">Suffix</label></td>
                    <td><span id="suffix"><?= $employeeDetails['emergency_contact_suffix'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="relationship">Relationship</label></td>
                    <td><span id="relationship"><?= $employeeDetails['emergency_contact_relationship'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="address">Address</label></td>
                    <td><span id="address"><?= $employeeDetails['emergency_contact_address'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="tel_no_home">Tel. No. (Home)</label></td>
                    <td><span id="tel_no_home"><?= $employeeDetails['emergency_contact_tel_home'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="tel_no_business">Tel. No. (Business)</label></td>
                    <td><span id="tel_no_business"><?= $employeeDetails['emergency_contact_tel_business'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="mobile_phone_no">Mobile Phone No.</label></td>
                    <td><span id="mobile_phone_no"><?= $employeeDetails['emergency_contact_mobile_phone'] ?? 'N/A' ?></span></td>
                    </tr>
                </table>
                </div>
            </div>
            </div>

            <!-- List of Organizations Been Part Of -->
            <div class="card mb-3">
            <div class="card-header2">List of Organizations Been Part Of</div>
            <div class="card-body2">
                <div class="table-responsive">
                <table class="table">
                    <tr>
                    <td><label for="organization">Organization</label></td>
                    <td><span id="organization"><?= $employeeDetails['organization'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="place">Place</label></td>
                    <td><span id="place"><?= $employeeDetails['place'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="date_of_membership">Date of Membership</label></td>
                    <td><span id="date_of_membership"><?= $employeeDetails['date_of_membership'] ?? 'N/A' ?></span></td>
                    </tr>
                    <tr>
                    <td><label for="position_held">Position Held</label></td>
                    <td><span id="position_held"><?= $employeeDetails['position_held'] ?? 'N/A' ?></span></td>
                    </tr>
                </table>
                </div>
            </div>
            </div>

                <div style="text-align: center;">
                    <div class="btn-container2">
                        <a href="edit_employee.php?id=<?= $id ?>" class="btn btn-warning">Edit</a>
                        <a href="delete_employee.php?id=<?= $id ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this employee?');">Delete</a>
                        <a href="employees.php" class="btn btn-secondary">Close</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        window.onload = function () {
        window.scrollTo(0, 0); 
        window.history.replaceState({}, document.title, window.location.pathname + window.location.search);
        };
    </script>

</body>
</html>