<?php
header('Content-Type: application/json'); // Set header to return JSON
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start(); // Keep session usage if needed elsewhere
ob_start(); // Start output buffering to prevent header issues

include 'db_config.php';
require '../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $applicant_id = $_POST['applicant_id'] ?? '';
    $email = $_POST['email'] ?? '';
    $schedule = $_POST['schedule'] ?? '';

    if (empty($email) || empty($schedule)) {
        echo json_encode(['status' => 'error', 'message' => 'Email and schedule are required.']);
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
        $mail->Subject = 'Interview Schedule Confirmation';
        $mail->Body = "<p>Dear Applicant,</p>
                       <p>Your interview has been scheduled on <strong>$schedule</strong>.</p>
                       <p>Best Regards,<br>Holy Spirit Human Resource Team</p>";

        $mail->send();

        // Update applicant status in the database
        $stmt = $conn->prepare("UPDATE applicants SET status = 'for_interview' WHERE applicant_id = ?");
        $stmt->bind_param("s", $applicant_id);
        $stmt->execute();

        echo json_encode(['status' => 'success', 'message' => 'Interview schedule sent successfully.']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error sending email: ' . $mail->ErrorInfo]);
    }

    exit();
}
?>
