    <?php
    session_name('staff_session');
    session_start();
    header('Content-Type: application/json');
    include 'db_config.php';

    if (!isset($_SESSION['employee_id'])) {
        echo json_encode([
            'status' => 'error',
            'title' => 'Unauthorized',
            'message' => 'Unauthorized access.'
        ]);
        exit;
    }

    $employee_id = $_SESSION['employee_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['leave_type_id'], $_POST['leave_dates'])) {
        $leave_type_id = intval($_POST['leave_type_id']);
        $leave_dates = $_POST['leave_dates']; // Format: "YYYY-MM-DD to YYYY-MM-DD"

        // Split the leave dates
   // Support single-day or range leave
        if (strpos($leave_dates, ' to ') !== false) {
            list($leave_start_date, $leave_end_date) = explode(" to ", $leave_dates);
        } else {
            $leave_start_date = $leave_end_date = $leave_dates;
        }

        // Calculate the total leave days
        $start_date = new DateTime($leave_start_date);
        $end_date = new DateTime($leave_end_date);
        $total_days = $start_date->diff($end_date)->days + 1; // Include the last day

        // Fetch max_days and total used days (only APPROVED)
        $fetchLeaveQuery = "
            SELECT lt.max_days, 
                COALESCE(SUM(CASE WHEN lr.status = 'Approved' THEN lr.total_days ELSE 0 END), 0) AS total_used_days
            FROM leave_types lt
            LEFT JOIN leave_requests lr ON lt.leave_type_id = lr.leave_type_id AND lr.employee_id = ?
            WHERE lt.leave_type_id = ?
            GROUP BY lt.leave_type_id, lt.max_days
        ";

        $stmt = $conn->prepare($fetchLeaveQuery);
        $stmt->bind_param("si", $employee_id, $leave_type_id);
        $stmt->execute();
        $leaveResult = $stmt->get_result();
        $leaveRow = $leaveResult->fetch_assoc();

        if (!$leaveRow) {
            echo json_encode([
                'status' => 'error',
                'title' => 'Error',
                'message' => 'Leave type does not exist.'
            ]);
            exit;
        }

        $max_days = $leaveRow['max_days'];
        $total_used_days = $leaveRow['total_used_days'];

        // Calculate remaining leave days
        $remaining_days = max($max_days - $total_used_days, 0);

        // Block leave request if no leave days are left or if the request exceeds the remaining balance
        if ($remaining_days <= 0) {
            echo json_encode([
                'status' => 'error',
                'title' => 'No Leave Left',
                'message' => '❌ Error: You have no leave days left for this type.'
            ]);
            exit;
        }

        if ($total_days > $remaining_days) {
            echo json_encode([
                'status' => 'warning',
                'title' => 'Insufficient Balance',
                'message' => "❌ Warning: You only have $remaining_days days available for this leave type."
            ]);
            exit;
        }

        // Insert leave request
        $insertRequestQuery = "INSERT INTO leave_requests (employee_id, leave_type_id, leave_start_date, leave_end_date, total_days, status, request_date) 
                            VALUES (?, ?, ?, ?, ?, 'Pending', NOW())";
        $stmt = $conn->prepare($insertRequestQuery);
        $stmt->bind_param("sissi", $employee_id, $leave_type_id, $leave_start_date, $leave_end_date, $total_days);

        if ($stmt->execute()) {
            echo json_encode([
                'status' => 'success',
                'title' => 'Success',
                'message' => '✅ Success: Your leave request has been recorded.'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'title' => 'SQL Error',
                'message' => '❌ SQL Error: ' . $stmt->error
            ]);
        }
    }
    ?>