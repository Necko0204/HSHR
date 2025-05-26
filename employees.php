<?php
session_name('admin_session');
session_start();
include 'includes/breadcrumb.php';
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


<!-- Main Content Wrapper -->
<main class="wrapper">
                <?php
                echo generateBreadcrumb();
                ?>
<div class="d-flex justify-content-start align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="fa fa-address-book"></i> Employee Masterlist</h2>
    <button class="btn btn-primary ms-auto d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
        <i class="fa fa-plus"></i> Add Employee
    </button>
</div>

<!-- Employee Table -->
<div class="card shadow-lg border-1 rounded-3">
    <div class="card-header bg-gradient-primary text-black d-flex justify-content-between align-items-center">
        <!-- Search Bar -->
        <div class="d-flex align-items-center">
            <input type="text" id="searchInput" class="form-control" placeholder="Search..." style="width: 150px;">
        </div>
        <!-- Title -->

        <!-- Archives Button -->
        <button type="button" class="btn btn-secondary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#ArchivesModal">
            <i class="fa fa-folder"></i> <span>Archives</span>
        </button>
    </div>
    <div class="card-body p-0">
    <div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
            <?php
            $sql = "SELECT e.id, e.lastname, e.firstname, e.gender, e.email1, e.salary, e.employment_type, e.status, sa.profile_picture 
            FROM employees e
            LEFT JOIN staff_accounts sa ON e.id = sa.employee_id
            WHERE e.status = 'Active'
            ORDER BY CAST(SUBSTRING(e.id, 9) AS UNSIGNED)";

            $result = $conn->query($sql);
            ?>
          <div style="max-height: 530px; overflow-y: auto;">
            <table class="table table-borderless table-hover align-middle">
                <thead class="table-light">
                    <tr>
                    <th class="d-none">ID</th>
                    <th style="min-width: 150px;">Profile Picture</th>
                    <th style="min-width: 150px;">Name</th>
                    <th style="min-width: 150px;">Gender</th>
                    <th style="min-width: 150px;">Email</th>
                    <th style="min-width: 150px;">Salary</th>
                    <th style="min-width: 150px;">Status</th>
                    <th style="min-width: 150px;">Account Status</th>
                    <th style="min-width: 150px;">Employment Type</th>
                    <th>Actions</th>
                    </tr>
                </thead>
                    <tbody id="employeesTable">
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <?php
                        // Check if the employee has an account
                        $account_sql = "SELECT employee_id FROM staff_accounts WHERE employee_id = ?";
                        $stmt = $conn->prepare($account_sql);
                        $stmt->bind_param("s", $row['id']);
                        $stmt->execute();
                        $account_result = $stmt->get_result();
                        $account_status = $account_result->num_rows > 0 ? 'Has Account' : 'No Account Found';
                        $stmt->close();
                        // Check if the status is Inactive
                        if ($row["status"] == 'Inactive') {
                            $account_status = 'Inactive';
                        }
                        ?>
                        <tr>
                            <td class="d-none"><?= htmlspecialchars($row["id"]) ?></td>
                            <td>
                                <?php if (!empty($row["profile_picture"])): ?>
                                    <img src="<?= htmlspecialchars(!empty($row["profile_picture"]) ? $row["profile_picture"] : 'images/image-not-found.jpg') ?>" 
                                        alt="Profile Picture" class="img-thumbnail" style="width: 125px; height: 125px;" 
                                        onclick="openProfileModal('<?= htmlspecialchars(!empty($row["profile_picture"]) ? $row["profile_picture"] : 'images/image-not-found.jpg') ?>')">
                                <?php else: ?>
                                    <img src="images/image-not-found.jpg" alt="Default Profile Picture" class="img-thumbnail" style="width: 125px; height: 125px;" data-bs-toggle="modal" data-bs-target="#profileModal<?= $row['id'] ?>">
                                <?php endif; ?>
                            </td>
                            <td ><?= htmlspecialchars($row["lastname"] . ', ' . $row["firstname"]) ?></td>
                            <td ><?= htmlspecialchars($row["gender"]) ?></td>
                            <td ><?= htmlspecialchars($row["email1"]) ?></td>
                            <td >&#8369; <?= htmlspecialchars($row["salary"]) ?></td>
                            <td >
                                <span class="badge <?= $row["status"] == 'Active' ? 'bg-success ' : 'bg-danger ' ?>">
                                    <?= htmlspecialchars($row["status"]) ?>
                                </span>
                            </td>
                            <td >
                                <span class="badge <?= $account_status == 'Has Account' ? 'bg-success ' : 'bg-danger ' ?>">
                                    <?= htmlspecialchars($account_status) ?>
                                </span>
                            </td>
                            <td >
                                <span class="badge <?= $row["employment_type"] == 'full_time' ? 'bg-primary ' : 'bg-warning ' ?>">
                                    <?= $row["employment_type"] == 'full_time' ? 'Full Time' : 'Part Time' ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-outline-info btn-sm rounded-pill shadow-sm " onclick="window.location.href='employee_details.php?id=<?= $row['id'] ?>'">
                                    <i class="bi bi-eye"></i> View Details
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            </div>
            <?php $conn->close(); ?>
        </div>
    </div>
</div>


<!-- Profile Picture Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">Profile Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="profileModalImage" src="" alt="Profile Picture" class="img-fluid rounded shadow-lg" style="max-width: 100%; height: auto; object-fit: cover;">
            </div>
        </div>
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
                        <form action="logics/add_employee_logic.php" id ="addEmployee" method="POST">
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
                                    <table class="table1 table-bordered" id="education-table">
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
                                    <div class="mb-3"></div>
                                    <div style="text-align: center;">
                                        <button type="button" class="btn btn-primary" onclick="addRow()">  <i class="fa fa-plus"></i> Add More</button>
                                    </div>    
                                </div>
                                </div>
                               
                                    <div class="mb=3">
                                    <div class="card-header">Section III: Employment/Occupational Background</div>
                                    <div class="card-body">
                                <table class="table1 table-bordered" id="employment-table">
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
                                    <table class="table1 table-bordered" id="organizations-table">
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
                                <div class="mb-3"></div>
                                    <div style="text-align: center;">
                                        <button type="button" class="btn btn-primary" id="add-row">
                                            <i class="fa fa-plus"></i> Add More
                                        </button>
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
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> Save
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
<!-- Archives Modal -->
<div class="modal fade" id="ArchivesModal" tabindex="-1" aria-labelledby="ArchivesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ArchivesModalLabel">Archives</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
                    <?php
                    include 'db_config.php';
                    $sql = "SELECT id, lastname, firstname, gender, email1, employment_type, status FROM employees WHERE status = 'Inactive'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0): 
                    ?>
                        <div style="max-height: 680px; overflow-y: auto;">
                        <table class="table table-borderless table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="d-none">ID</th>
                                    <th style="min-width: 150px;">Last Name</th>
                                    <th style="min-width: 150px;">First Name</th>
                                    <th style="min-width: 150px;">Gender</th>
                                    <th style="min-width: 150px;">Email</th>
                                    <th style="min-width: 150px;">Status</th>
                                    <th style="min-width: 150px;">Employment Type</th>
                                </tr>
                            </thead>
                            <tbody id="employeesTable">
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <?php
                                    // Check if the employee has an account
                                    $account_sql = "SELECT employee_id FROM staff_accounts WHERE employee_id = ?";
                                    $stmt = $conn->prepare($account_sql);
                                    $stmt->bind_param("s", $row['id']);
                                    $stmt->execute();
                                    $account_result = $stmt->get_result();
                                    $account_status = $account_result->num_rows > 0 ? 'Has Account' : 'No Account Found';
                                    $stmt->close();
                                    ?>
                                    <tr class="text-dark">
                                        <td class="d-none"><?= htmlspecialchars($row["id"]) ?></td>
                                        <td ><?= htmlspecialchars($row["lastname"]) ?></td>
                                        <td ><?= htmlspecialchars($row["firstname"]) ?></td>
                                        <td ><?= htmlspecialchars($row["gender"]) ?></td>
                                        <td ><?= htmlspecialchars($row["email1"]) ?></td>
                                        <td >
                                            <span class="badge bg-danger "><?= htmlspecialchars($row["status"]) ?></span>
                                        </td>
                                        <td >
                                            <span class="badge <?= $row["employment_type"] == 'full_time' ? 'bg-primary ' : 'bg-warning' ?>">
                                                <?= $row["employment_type"] == 'full_time' ? 'Full Time' : 'Part Time' ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <h5 class="text-muted">No Inactive employees found.</h5>
                        </div>
                    <?php endif; ?>
                </div>
                <?php $conn->close(); ?>
                </div>
                <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Close
                        </button>
                    </div>
            </div>
            
        </div>
    </div>  
<script src="background.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "timeOut": "1000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};                          // Function to dynamically add rows to the table in Section II
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

 $(document).ready(function () {
    $("#addEmployee").submit(function (e) {
        e.preventDefault(); // Prevent default form submission

        var formData = new FormData(this); // Create form data object

        $.ajax({
            url: "logics/add_employee_logic.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function () {
                        window.location.reload(); // Reload window after success
                    }, 1500);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function () {
                toastr.error("An error occurred. Please try again.");
            }
        });
    });
});


    </script>
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
<script>
    document.getElementById("searchInput").addEventListener("keyup", function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#employeesTable tr");

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});
</script>
<script>
function openProfileModal(imageSrc) {
    document.getElementById("profileModalImage").src = imageSrc;
    var modal = new bootstrap.Modal(document.getElementById("profileModal"));
    modal.show();
}
</script>

</body>


</html>