<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

require '../vendor/autoload.php'; // Load PHPMailer and QR Code libraries (Composer required)
include 'db_config.php'; // Database connection
date_default_timezone_set('Asia/Manila'); // Set timezone

header('Content-Type: application/json');
$response = ["success" => false, "message" => ""];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['applicant_id'])) {
    $applicant_id = trim($_POST['applicant_id']);
    $applicant_id = htmlspecialchars($applicant_id);

    error_log("Applicant ID received: " . $applicant_id);

    // 🔹 Fetch applicant details
    $query = "SELECT lastname, firstname, middlename, gender, dateofbirth, contact, email 
              FROM applicants WHERE applicant_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $applicant_id);
    $stmt->execute();
    $stmt->bind_result($lastname, $firstname, $middlename, $gender, $dateofbirth, $mobilephone, $email1);

    if ($stmt->fetch()) {
        $stmt->close();

        // 🔹 Generate Employee ID with fixed "HS-EID2020" format
        $prefix = "HS-EID2020"; // Fixed prefix
        $likePrefix = $prefix . "%";

        // 🔹 Get the last inserted ID
        $getLastID = "SELECT id FROM employees WHERE id LIKE ? 
                      ORDER BY CAST(SUBSTRING(id, 12) AS UNSIGNED) DESC LIMIT 1";
        $stmt = $conn->prepare($getLastID);
        $stmt->bind_param("s", $likePrefix);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastID = $row['id'];

            $lastNumber = (int)substr($lastID, -7); // Extract last 7 digits
            $newNumber = str_pad($lastNumber + 1, 7, "0", STR_PAD_LEFT);
        } else {
            $newNumber = "0000001";
        }
        $stmt->close();

        $newEmployeeID = $prefix . $newNumber;

        // 🔹 Prevent duplicate insertion
        $checkExists = "SELECT id FROM employees WHERE id = ?";
        $stmt = $conn->prepare($checkExists);
        $stmt->bind_param("s", $newEmployeeID);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) { // Only insert if it doesn't exist
            $stmt->close();

            // 🔹 Insert into employees table
            $insertQuery = "INSERT INTO employees (
                id, lastname, firstname, middlename, gender, dateofbirth, mobilephone, email1, status, employment_type, datejoiningindsclc
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Active', 'Full-Time', NOW())";

            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("ssssssss", $newEmployeeID, $lastname, $firstname, $middlename, $gender, $dateofbirth, $mobilephone, $email1);

            if ($stmt->execute()) {
                // 🔹 Insert into ed_2ndhalf table (only employee_id)
                $insertEd2ndHalf = "INSERT INTO ed_2ndhalf (employee_id) VALUES (?)";
                $stmtEd2ndHalf = $conn->prepare($insertEd2ndHalf);
                $stmtEd2ndHalf->bind_param("s", $newEmployeeID);
            
                if ($stmtEd2ndHalf->execute()) {
                    error_log("Employee ID inserted into ed_2ndhalf successfully.");
                } else {
                    error_log("Failed to insert into ed_2ndhalf: " . $stmtEd2ndHalf->error);
                }
                $stmtEd2ndHalf->close();
            
                // 🔹 Update Applicant Status
                $updateApplicant = "UPDATE applicants SET status = 'Accepted' WHERE applicant_id = ?";
                $updateStmt = $conn->prepare($updateApplicant);
                $updateStmt->bind_param("s", $applicant_id);
            
                if ($updateStmt->execute()) {
                    // 🔹 Send Acceptance Email
                    if (sendAcceptanceEmail($firstname, $lastname, $email1, $newEmployeeID)) {
                        $response["success"] = true;
                        $response["message"] = "Applicant successfully added as an employee! New Employee ID: " . $newEmployeeID;
                    } else {
                        $response["message"] = "Employee added, but email failed to send.";
                    }
                } else {
                    $response["message"] = "Failed to update applicant status.";
                }
                $updateStmt->close();
            } else {
                $response["message"] = "Error inserting employee: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $response["message"] = "Duplicate Employee ID detected: " . $newEmployeeID;
        }
    } else {
        $response["message"] = "Applicant not found.";
    }
} else {
    $response["message"] = "Invalid request.";
}

$conn->close();
echo json_encode($response);

/**
 * 🔹 Send Acceptance Email using PHPMailer
 */
function sendAcceptanceEmail($firstname, $lastname, $email, $employeeID) {
    $mail = new PHPMailer(true);

    try {
        // Generate QR Code with URL
        $loginUrl = "http://localhost/HSHR/staff_side/index.php" . urlencode($employeeID);
        $qrCode = new QrCode($loginUrl);
        $writer = new PngWriter();
        $qrCodeImage = $writer->write($qrCode)->getString();

        // Save QR Code to a temporary file
        $qrCodePath = tempnam(sys_get_temp_dir(), 'qrcode') . '.png';
        file_put_contents($qrCodePath, $qrCodeImage);

        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = getenv('EMAIL_USERNAME') ?: 'mendoza.marcangelo28@gmail.com'; 
        $mail->Password = getenv('EMAIL_PASSWORD') ?: 'jjmv pgae dlhh kmqp'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email Headers
        $mail->setFrom('mendoza.marcangelo28@gmail.com', 'Marc Angelo Mendoza');
        $mail->addAddress($email, "$firstname $lastname");

        // 🔹 Email Content
        $mail->isHTML(true);
        $mail->Subject = "🎉 Congratulations! Welcome to Our Team!";
        $mail->Body = "
            <h2>Dear $firstname $lastname,</h2>
            <p>We are thrilled to inform you that you have been officially accepted into our team!</p>
            <p>Your Employee ID: <strong>$employeeID</strong></p>
            <p>You can log in to your account using the following link: <a href='$loginUrl'>$loginUrl</a></p>
            <p>Your account details will be emailed to you soon.</p>
            <br>
            <p>Best regards,</p>
            <p><strong>Company Name</strong></p>
            <p><img src='cid:qrcode'></p>
        ";

        // Attach QR Code
        $mail->addEmbeddedImage($qrCodePath, 'qrcode');

        // 🔹 Send Email
        return $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    } finally {
        // Clean up temporary file
        if (file_exists($qrCodePath)) {
            unlink($qrCodePath);
        }
    }
}
?>
