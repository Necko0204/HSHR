<?php
header('Content-Type: application/json'); // Set header to return JSON
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start(); // Keep session active if needed elsewhere
include 'db_config.php';
require '../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $applicant_id = $_POST['applicant_id'] ?? '';
    $email = $_POST['email'] ?? '';

    if (empty($applicant_id) || empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Applicant ID and email are required.']);
        exit();
    }

    $mail = new PHPMailer(true);
    try {
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
        $mail->addAddress($email);

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = 'Application Update - Holy Spirit Human Resource';
        $mail->Body = "<p>Dear Applicant,</p>
                       <p>We appreciate your interest in joining our team. However, after careful consideration, 
                       we regret to inform you that we will not be moving forward with your application.</p>
                       <p>Thank you for your time and interest.</p>
                       <p>Best Regards,<br>Holy Spirit Human Resource Team</p>";

        $mail->send();

        // Update applicant status to "rejected" in the database
        $stmt = $conn->prepare("UPDATE applicants SET status = 'rejected' WHERE applicant_id = ?");
        $stmt->bind_param("s", $applicant_id);
        $stmt->execute();

        echo json_encode(['status' => 'success', 'message' => 'Applicant has been rejected and notified.']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error sending rejection email: ' . $mail->ErrorInfo]);
    }

    exit();
}
?>
