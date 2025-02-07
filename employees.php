<?php
session_name('admin_session');
session_start();

include 'db_config.php';
include 'helper.php';

// Debug: Check if session is properly set
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="background.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: white;
            color: black;
            min-height: 100vh;
            margin: 0; /* Reset margin to ensure no unwanted space */
            overflow: hidden; /* Hide scroll wheel */
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
            .hidden-id {
            display: none;
        }
            
            </style>
        </head>
<body>
    <!-- Sidebar & Navbar-->
    <?php include 'sidebar.php'; ?>
    <?php include 'nav_header.php'; ?>

<!-- Main Content Wrapper -->
<main class="wrapper">
<section class="content">
    <div class="d-flex justify-content-start align-items-center">
        <h2 class="fw-bold text-dark mb-0">Employee Masterlist</h2>
        <button class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
            Add Employee
        </button>
    </div>
</section>

        <!-- Employee Table -->
        <div class="card">
            <div class="card-header">Employee List</div>
            <div class="card-body table-responsive">
                <?php
                $sql = "SELECT id, lastname, firstname, gender, email1, status FROM employees";
                $result = $conn->query($sql);
                ?>

                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                        <th class="hidden-id">ID</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="hidden-id"><?= htmlspecialchars($row["id"]) ?></td>
                                <td><?= htmlspecialchars($row["lastname"]) ?></td>
                                <td><?= htmlspecialchars($row["firstname"]) ?></td>
                                <td><?= htmlspecialchars($row["gender"]) ?></td>
                                <td><?= htmlspecialchars($row["email1"]) ?></td>
                                <td>
                                    <span class="<?= $row["status"] == 'Active' ? 'badge-active' : 'badge-inactive' ?>">
                                        <?= htmlspecialchars($row["status"]) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="employee_details.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">View Details</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php $conn->close(); ?>
            </div>
        </div>

       <!-- Add Employee Modal -->
                                                                                                   
        <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEmployeeModalLabel">Add Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="submit_employee_data.php" method="POST">
                            <!-- Personal Information -->
                            <div class="card-header">Section I: Personal Data of Applicant</div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="lastname" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="lastname" name="lastname" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="firstname" class="form-label">First Name</label>
                                        <input type="text" class="form-control" id="firstname" name="firstname" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="middlename" class="form-label">Middle Name</label>
                                        <input type="text" class="form-control" id="middlename" name="middlename" value="<?= $row['middlename'] ?? '' ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label for="suffix" class="form-label">Suffix</label>
                                        <input type="text" class="form-control" id="suffix" name="suffix" value="<?= $row['suffix'] ?? '' ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select class="form-control" id="gender" name="gender" required>
                                            <option value="" disabled selected>Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>    
                                    <div class="mb-3">
                                        <label for="othernamesused" class="form-label">Other Names Used</label>
                                        <input type="text" class="form-control" id="othernamesused" name="othernamesused" value="<?= $row['othernamesused'] ?? '' ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="homeaddress" class="form-label">Home Address</label>
                                        <textarea class="form-control" id="homeaddress" name="homeaddress"><?= $row['homeaddress'] ?? '' ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="maritalstatus" class="form-label">Marital Status</label>
                                        <input type="text" class="form-control" id="maritalstatus" name="maritalstatus" value="<?= $row['maritalstatus'] ?? '' ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="nameofspouse" class="form-label">Name of Spouse</label>
                                        <input type="text" class="form-control" id="nameofspouse" name="nameofspouse" value="<?= $row['nameofspouse'] ?? '' ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="no_of_children" class="form-label">Number of Children</label>
                                        <input type="number" class="form-control" id="no_of_children" name="no_of_children" value="<?= $row['no_of_children'] ?? '' ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="kidsnames" class="form-label">Kids Names</label>
                                        <input type="text" class="form-control" id="kidsnames" name="kidsnames" value="<?= $row['kidsnames'] ?? '' ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="height" class="form-label">Height</label>
                                        <input type="text" class="form-control" id="height" name="height" value="<?= $row['height'] ?? '' ?>">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="weight" class="form-label">Weight</label>
                                        <input type="text" class="form-control" id="weight" name="weight" value="<?= $row['weight'] ?? '' ?>">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="eyecolor" class="form-label">Eye Color</label>
                                        <input type="text" class="form-control" id="eyecolor" name="eyecolor" value="<?= $row['eyecolor'] ?? '' ?>">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="haircolor" class="form-label">Hair Color</label>
                                        <input type="text" class="form-control" id="haircolor" name="haircolor" value="<?= $row['haircolor'] ?? '' ?>">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="distinguishingfeatures" class="form-label">Distinguishing Features</label>
                                        <input type="text" class="form-control" id="distinguishingfeatures" name="distinguishingfeatures" value="<?= $row['distinguishingfeatures'] ?? '' ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="dateofbirth" class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control" id="dateofbirth" name="dateofbirth" value="<?= $row['dateofbirth'] ?? '' ?>" onchange="calculateAge()">
                                    </div>

                                    <div class="mb-3">
                                        <label for="age" class="form-label">Age</label>
                                        <input type="text" class="form-control" id="age" name="age" readonly>
                                    </div>
                                        <script>
                                            function calculateAge() {
                                                var dob = document.getElementById('dateofbirth').value;
                                                if (dob) {
                                                    var birthDate = new Date(dob);
                                                    var today = new Date();
                                                    var age = today.getFullYear() - birthDate.getFullYear();
                                                    var m = today.getMonth() - birthDate.getMonth();

                                                    // Adjust if birthday hasn't occurred yet this year
                                                    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                                                        age--;
                                                    }

                                                    // Display the age
                                                    document.getElementById('age').value = age;
                                                }
                                            }
                                        </script>       
                                    <div class="mb-3">
                                        <label for="placeofbirth" class="form-label">Place of Birth</label>
                                        <input type="text" class="form-control" id="placeofbirth" name="placeofbirth" value="<?= $row['placeofbirth'] ?? '' ?>">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="citizenship" class="form-label">Citizenship</label>
                                        <input type="text" class="form-control" id="citizenship" name="citizenship" value="<?= $row['citizenship'] ?? '' ?>">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="provinceoforigin" class="form-label">Province of Origin</label>
                                        <input type="text" class="form-control" id="provinceoforigin" name="provinceoforigin" value="<?= $row['provinceoforigin'] ?? '' ?>">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="religion" class="form-label">Religion</label>
                                        <input type="text" class="form-control" id="religion" name="religion" value="<?= $row['religion'] ?? '' ?>">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="bloodtype" class="form-label">Blood Type</label>
                                        <input type="text" class="form-control" id="bloodtype" name="bloodtype" value="<?= $row['bloodtype'] ?? '' ?>">
                                    </div> 
                                    <div class="mb-3">
                                        <label for="workoccupation" class="form-label">Work Occupation</label>
                                        <input type="text" class="form-control" id="workoccupation" name="workoccupation" value="<?= $row['workoccupation'] ?? '' ?>">
                                    </div>
                                </div>
                                    <!-- Contact Information -->
                                    <div class="card-header">Contact Information</div>
                                    <div class="card-body">
                                            <div class="mb-3">
                                                <label for="homephone" class="form-label">Home Phone</label>
                                                <input type="text" class="form-control" id="homephone" name="homephone" value="<?= $row['homephone'] ?? '' ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="businessphone" class="form-label">Business Phone</label>
                                                <input type="text" class="form-control" id="businessphone" name="businessphone" value="<?= $row['businessphone'] ?? '' ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="mobilephone" class="form-label">Mobile Phone</label>
                                                <input type="text" class="form-control" id="mobilephone" name="mobilephone" value="<?= $row['mobilephone'] ?? '' ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="email1" class="form-label">Email 1</label>
                                                <input type="email" class="form-control" id="email1" name="email1" value="<?= $row['email1'] ?? '' ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="email2" class="form-label">Email 2</label>
                                                <input type="email" class="form-control" id="email2" name="email2" value="<?= $row['email2'] ?? '' ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="faxno" class="form-label">Fax No</label>
                                                <input type="text" class="form-control" id="faxno" name="faxno" value="<?= $row['faxno'] ?? '' ?>">
                                            </div>  
                                            <div class="mb-3">
                                                <label for="mobilephone" class="form-label">Mobile Phone</label>
                                                <input type="text" class="form-control" id="mobilephone" name="mobilephone" value="<?= $row['mobilephone'] ?? '' ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="fbvibername" class="form-label">FB/Viber Name</label>
                                                <input type="text" class="form-control" id="fbvibername" name="fbvibername" value="<?= $row['fbvibername'] ?? '' ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="instagram" class="form-label">Instagram</label>
                                                <input type="text" class="form-control" id="instagram" name="instagram" value="<?= $row['instagram'] ?? '' ?>">
                                            </div>
                                        </div>
                                
                                    <!-- Government and Legal Documents -->
                                    <div class="card-header">Government and Legal Documents</div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="sss_gsis" class="form-label">SSS/GSIS No</label>
                                            <input type="text" class="form-control" id="sss_gsis" name="sss_gsis" value="<?= $row['sss_gsis'] ?? '' ?>">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="passportno" class="form-label">Passport No</label>
                                            <input type="text" class="form-control" id="passportno" name="passportno" value="<?= $row['passportno'] ?? '' ?>">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="expirydate" class="form-label">Expiry Date</label>
                                            <input type="date" class="form-control" id="expirydate" name="expirydate" value="<?= $row['expirydate'] ?? '' ?>">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="typeofvisa" class="form-label">Type of Visa</label>
                                            <input type="text" class="form-control" id="typeofvisa" name="typeofvisa" value="<?= $row['typeofvisa'] ?? '' ?>">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="tinno" class="form-label">TIN No</label>
                                            <input type="text" class="form-control" id="tinno" name="tinno" value="<?= $row['tinno'] ?? '' ?>">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="datejoiningindsclc" class="form-label">Date joining in DSCLC</label>
                                            <input type="date" class="form-control" id="datejoiningindsclc" name="datejoiningindsclc" value="<?= $row['datejoiningindsclc'] ?? '' ?>">
                                        </div>
                                     </div>
                                    <div class="card-header">Section II: Educational Background</div>
                                        <div class="card-body"> 
                                        <div class="mb=3">
                                    <table class="table table-bordered" id="education-table">
                                    <thead>
                                        <tr>
                                            <th>Name of School</th>
                                            <th>Degree(s) Obtained</th>
                                            <th>Inclusive Dates</th>
                                            <th>Year Obtained</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" class="form-control" name="schoolname[]" value="<?= $row['schoolname'] ?? '' ?>"></td>
                                            <td><input type="text" class="form-control" name="degreeobtained[]" value="<?= $row['degreeobtained'] ?? '' ?>"></td>
                                            <td><input type="text" class="form-control" name="inclusivedates[]" value="<?= $row['inclusivedates'] ?? '' ?>"></td>
                                            <td><input type="number" class="form-control" name="yearobtained[]" value="<?= $row['yearobtained'] ?? '' ?>"></td>
                                        </tr>
                                    </tbody>
                                    </table>

                                    <!-- Add More Button -->
                                    <div style="text-align: center;">
                                        <button type="button" class="btn btn-primary" onclick="addRow()">Add More</button>
                                    </div>    
                                </div>
                                </div>
                               
                                
                                    <div class="mb=3">
                                    <div class="card-header">Section III: Employment/Occupational Background</div>
                                    <div class="card-body">
                                <table class="table table-bordered" id="employment-table">
                                    <thead>
                                        <tr>
                                            <th>Company</th>
                                            <th>Nature of Business</th>
                                            <th>Designation</th>
                                            <th>Inclusive Dates</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <tr>
                                                <td><input type="text" class="form-control" name="company[]" value="<?= $row['company'] ?? '' ?>"></td>
                                                <td><input type="text" class="form-control" name="natureofbusiness[]" value="<?= $row['natureofbusiness'] ?? '' ?>"></td>
                                                <td><input type="text" class="form-control" name="designation[]" value="<?= $row['designation'] ?? '' ?>"></td>
                                                <td><input type="text" class="form-control" name="inclusivedates[]" value="<?= $row['inclusivedates'] ?? '' ?>"></td>
                                            </tr>
                                    </tbody>
                                </table>
                                                (For past or currently in Government Position - Please state highest Appointed/Elected Office)
                                    <div class="form-group">
                                            <label>Position/Period Assumed:</label>
                                                <input type="text" class="form-control" name="position_period_assumed" value="<?= $row['position_period_assumed'] ?? '' ?>">
                                    </div>

                                    <div class="form-group">
                                            <label>Nature of Office:</label>
                                                <input type="text" class="form-control" name="nature_of_office" value="<?= $row['nature_of_office'] ?? '' ?>">
                                    </div>
                    </div>
                                    <div class="mb=3">
                                    <div class="card-header">Section IV: Additional Data</div>
                                    <div class="card-body">

                                    <div class="mb-3">
                                            <label for="professional_licenses" class="form-label">Professional Licenses:</label>
                                            <input type="text" class="form-control" name="professional_licenses[]" id="professional_licenses" value="<?= $row['professional_licenses'] ?? '' ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="special_trainings" class="form-label">Special Trainings:</label>
                                            <input type="text" class="form-control" name="special_trainings[]" id="special_trainings" value="<?= $row['special_trainings'] ?? '' ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="special_interests_skills" class="form-label">Special Interests/Skills:</label>
                                            <input type="text" class="form-control" name="special_interests_skills[]" id="special_interests_skills" value="<?= $row['special_interests_skills'] ?? '' ?>">
                                        </div>
                                    </div>
                                    <div class="mb=3">
                                    <div class="card-header">Section V: Emergency Contact</div>
                                    <div class="card-body">
                                    <div class="mb-3">
                                            <label for="last_name" class="form-label">Last Name:</label>
                                            <input type="text" class="form-control" name="last_name" id="last_name" value="<?= $row['last_name'] ?? '' ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="first_name" class="form-label">First Name:</label>
                                            <input type="text" class="form-control" name="first_name" id="first_name" value="<?= $row['first_name'] ?? '' ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="middle_initial" class="form-label">Middle Initial:</label>
                                            <input type="text" class="form-control" name="middle_initial" id="middle_initial" value="<?= $row['middle_initial'] ?? '' ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="suffix" class="form-label">Suffix:</label>
                                            <input type="text" class="form-control" name="suffix" id="suffix" value="<?= $row['suffix'] ?? '' ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="relationship" class="form-label">Relationship:</label>
                                            <input type="text" class="form-control" name="relationship" id="relationship" value="<?= $row['relationship'] ?? '' ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address:</label>
                                            <input type="text" class="form-control" name="address" id="address" value="<?= $row['address'] ?? '' ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="tel_home" class="form-label">Tel. No. (Home):</label>
                                            <input type="text" class="form-control" name="tel_home" id="tel_home" value="<?= $row['tel_home'] ?? '' ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="tel_business" class="form-label">Tel. No. (Business):</label>
                                            <input type="text" class="form-control" name="tel_business" id="tel_business" value="<?= $row['tel_business'] ?? '' ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="mobile_phone" class="form-label">Mobile Phone No.:</label>
                                            <input type="text" class="form-control" name="mobile_phone" id="mobile_phone" value="<?= $row['mobile_phone'] ?? '' ?>">
                                        </div>
                                    </div>
                                    <div class="mb=3">
                                    <div class="card-header"></div>
                                    <div class="card-body">
                                    <h5>List of Organizations or Social Groups Which You Have Been a Member Of:</h5>
                                    <table class="table table-bordered" id="organizations-table">
                                    <thead>
                                        <tr>
                                            <th>Organization</th>
                                            <th>Place</th>
                                            <th>Date of Membership</th>
                                            <th>Position Held</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" class="form-control" name="organization[]" value="<?= $row['organization'] ?? '' ?>"></td>
                                            <td><input type="text" class="form-control" name="place[]" value="<?= $row['place'] ?? '' ?>"></td>
                                            <td><input type="text" class="form-control" name="date_of_membership[]" value="<?= $row['date_of_membership'] ?? '' ?>"></td>
                                            <td><input type="text" class="form-control" name="position_held[]" value="<?= $row['position_held'] ?? '' ?>"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                    <div style="text-align: center;">
                                        <button type="button" class="btn btn-primary" id="add-row">Add More</button>
                                    </div>
                                    <div class="mb=3">
                                    <div class="card-header">SECTION IX: REQUIRED CLEARANCES AND ATTACHMENTS</div>
                                    <div class="card-body">

                                    <div class="mb-3">
                                            <label for="nbi_clearance" class="form-label">a. NBI CLEARANCE:</label>
                                            <input type="file" class="form-control" name="nbi_clearance" id="nbi_clearance">
                                        </div>

                                        <div class="mb-3">
                                            <label for="police_clearance" class="form-label">b. POLICE CLEARANCE:</label>
                                            <input type="file" class="form-control" name="police_clearance" id="police_clearance">
                                        </div>

                                        <div class="mb-3">
                                            <label for="barangay_clearance" class="form-label">c. BARANGAY CLEARANCE:</label>
                                            <input type="file" class="form-control" name="barangay_clearance" id="barangay_clearance">
                                        </div>

                                        <div class="mb-3">
                                            <label for="orientation_certificate" class="form-label">d. ORIENTATION AND MEMBERSHIP SEMINAR CERTIFICATE (copy):</label>
                                            <input type="file" class="form-control" name="orientation_certificate" id="orientation_certificate">
                                        </div>

                                        </div>
                                        </div>
                            <button type="submit" class="btn btn-success">Save</button>
                </form>
                </div>
    
    </div>
</div>


    </main>
        <!-- Bootstrap JS -->
         <script src="background.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
                                    // Function to dynamically add rows to the table in Section II
                                    function addRow() {
                                            var table = document.querySelector("#education-table tbody");  // Get the tbody part of the table
                                            var newRow = table.insertRow();  // Insert a new row at the end of the tbody

                                            // Add new cells with input fields and a remove button in the new row
                                            newRow.innerHTML = `
                                                <td><input type="text" class="form-control" name="schoolname[]" value=""></td>
                                                <td><input type="text" class="form-control" name="degreeobtained[]" value=""></td>
                                                <td><input type="text" class="form-control" name="inclusivedates[]" value=""></td>
                                                <td><input type="number" class="form-control" name="yearobtained[]" value=""></td>
                                                <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>
                                            `;
                                        }

                                        // Function to remove a row
                                        function removeRow(button) {
                                            var row = button.parentElement.parentElement;  // Get the row of the clicked button
                                            row.remove();  // Remove the row from the table
                                        }
    </script>
    <script>
                                        document.getElementById('add-row').addEventListener('click', function () {
                                            let tableBody = document.querySelector("#organizations-table tbody");
                                            let newRow = document.createElement("tr");
                                            newRow.innerHTML = `
                                                <td><input type="text" class="form-control" name="organization[]" value=""></td>
                                                <td><input type="text" class="form-control" name="place[]" value=""></td>
                                                <td><input type="text" class="form-control" name="date_of_membership[]" value=""></td>
                                                <td><input type="text" class="form-control" name="position_held[]" value=""></td>
                                                <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                                            `;
                                            tableBody.appendChild(newRow);
                                        });

                                        document.addEventListener("click", function (e) {
                                            if (e.target.classList.contains("remove-row")) {
                                                e.target.closest("tr").remove();
                                            }
                                        });
                                       
</script>

</body>


</html>