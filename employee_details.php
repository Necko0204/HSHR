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
        <form action="submit_employee_data.php" method="POST">

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

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Submit</button>

        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

