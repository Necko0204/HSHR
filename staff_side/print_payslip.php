<?php
require_once __DIR__ . '/includes/staff_session.php';

error_reporting(E_ALL);
ini_set('display_errors', '0');

require_once __DIR__ . '/../vendor/tecnickcom/tcpdf/tcpdf.php';
include '../includes/db_config.php';

$employee_id = $_SESSION['employee_id'] ?? null;
if (!$employee_id) {
    die('Unauthorized access');
}

if (isset($_GET['action']) && $_GET['action'] === 'print_payslip' && isset($_GET['month'])) {
    $selected_month = trim((string) $_GET['month']);
    if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $selected_month)) {
        http_response_code(422);
        die('Invalid month format.');
    }

    // Get employee details
    $emp_query = $conn->prepare("SELECT firstname, lastname, salary FROM employees WHERE id = ?");
    $emp_query->bind_param("s", $employee_id);
    $emp_query->execute();
    $emp_result = $emp_query->get_result();
    $emp_row = $emp_result->fetch_assoc();

    if (!$emp_row) {
        die('Employee not found');
    }

    $employee_name = $emp_row['firstname'] . ' ' . $emp_row['lastname'];
    $monthly_salary = floatval($emp_row['salary']);

    // Get SSS deduction based on salary range
    $sss_query = $conn->prepare("SELECT employee_share FROM sss_deductions WHERE ? BETWEEN salary_base AND (salary_base + 499.99) LIMIT 1");
    $sss_query->bind_param("d", $monthly_salary);
    $sss_query->execute();
    $sss_result = $sss_query->get_result();
    $sss_row = $sss_result->fetch_assoc();

    $sss_deduction = $sss_row['employee_share'] ?? 0;

    // Determine date range
    $manilaTimezone = new DateTimeZone('Asia/Manila');
    $dateObj = DateTimeImmutable::createFromFormat('!Y-m', $selected_month, $manilaTimezone);
    if (!$dateObj) {
        http_response_code(422);
        die('Invalid month format.');
    }
    $days_in_month = (int)$dateObj->format('t');

    // Get employee work schedule
    $schedule_query = $conn->prepare("SELECT day_of_week, required_hours FROM work_schedules WHERE employee_id = ?");
    $schedule_query->bind_param("s", $employee_id);
    $schedule_query->execute();
    $schedule_result = $schedule_query->get_result();

    $hours_by_day = [];
    while ($row = $schedule_result->fetch_assoc()) {
        $hours_by_day[$row['day_of_week']] = floatval($row['required_hours']);
    }

    // Calculate total required hours in the month
    $total_required_hours = 0;
    for ($day = 1; $day <= $days_in_month; $day++) {
        $current_date = $dateObj->format('Y-m-') . str_pad($day, 2, '0', STR_PAD_LEFT);
        $day_of_week = date('l', strtotime($current_date));
        $total_required_hours += $hours_by_day[$day_of_week] ?? 0;
    }

    // Match the payroll summary fallback when HR has not configured a schedule yet.
    $uses_fallback_rate = $total_required_hours <= 0 || $monthly_salary <= 0;
    $hourly_rate = $uses_fallback_rate ? 50.0 : $monthly_salary / $total_required_hours;

    // Get total worked hours
    $stmt = $conn->prepare("
        SELECT SEC_TO_TIME(SUM(
            COALESCE(
                TIME_TO_SEC(total_hours),
                GREATEST(
                    TIME_TO_SEC(TIMEDIFF(time_out, time_in)) - COALESCE(TIME_TO_SEC(break_duration), 0),
                    0
                )
            )
        )) AS total_time
        FROM attendance
        WHERE employee_id = ?
          AND DATE_FORMAT(date, '%Y-%m') = ?
          AND time_in IS NOT NULL
          AND time_out IS NOT NULL
    ");
    $stmt->bind_param("ss", $employee_id, $selected_month);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $total_time = $row['total_time'] ?: '00:00:00';
    list($hh, $mm, $ss) = explode(':', $total_time);
    $total_worked_hours = $hh + ($mm / 60) + ($ss / 3600);

    $gross_pay = $total_worked_hours * $hourly_rate;
    $net_pay = $gross_pay - $sss_deduction;

    // TCPDF can render this JPEG directly without optional GD/Imagick extensions.
    $logoPath = realpath(__DIR__ . '/../images/asdasdasd123123123123123.jpg') ?: '';
    $generatedAt = new DateTimeImmutable('now', $manilaTimezone);

    // Setup TCPDF
    $pdf = new TCPDF('P', 'mm', 'A6', true, 'UTF-8', false);
    $pdf->SetCreator('HSHR System');
    $pdf->SetAuthor('HSHR');
    $pdf->SetTitle('Payslip');
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddPage();
    $pdf->SetFont('dejavusans', '', 8);

    // HTML for payslip
    $html = '
    <div style="text-align:center;">
    <!-- Header -->
    <div style="text-align:center; margin-bottom:2px;">
        <img src="' . htmlspecialchars($logoPath, ENT_QUOTES, 'UTF-8') . '" style="height:36px; margin-bottom:1px;">
        <div style="font-size:7.5pt; font-weight:bold;">Holy Spirit School of Imus Inc.</div>
        <div style="font-size:6pt;">Human Resource Department</div>
        <div style="font-size:6pt; color:#555;">Anabu, Imus, Cavite | Contact: 0960-216-1734</div>
    </div>

    </div>
    <hr style="border:0;border-top:1px solid #bbb;margin:2px 0;">
    <div style="text-align:center;font-size:8pt;font-weight:bold;background-color:#f5f5f5;padding:2px 0;margin:3px 0 6px 0;letter-spacing:1px;color:#34495e;">PAYSLIP</div>
    <table cellpadding="0" style="width:100%;font-size:7pt;">
        <tr>
            <td><strong>Name:</strong></td>
            <td>' . htmlspecialchars($employee_name) . '</td>
        </tr>
        <tr>
            <td><strong>Month:</strong></td>
            <td>' . $dateObj->format('F Y') . '</td>
        </tr>
        <tr>
            <td><strong>Generated:</strong></td>
            <td>' . $generatedAt->format('M d, Y h:i A') . ' (GMT+8)</td>
        </tr>
        <tr>
            <td><strong>Payslip #:</strong></td>
            <td>' . strtoupper(substr(md5($employee_id . $selected_month), 0, 8)) . '</td>
        </tr>
    </table>
    <br>
   <!-- Salary Breakdown -->
    <table cellpadding="2" border="1" cellspacing="0" style="width:100%; margin-top:4px; font-size:6.5pt; border-collapse:collapse;">
        <thead>
            <tr style="background:#eaeaea;">
                <th style="text-align:left;">Description</th>
                <th style="text-align:right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Monthly Salary</td><td align="right">&#8369;' . number_format($monthly_salary, 2) . '</td></tr>
            <tr><td>Hourly Rate</td><td align="right">&#8369;' . number_format($hourly_rate, 2) . '</td></tr>
            <tr><td>Worked Hours</td><td align="right">' . number_format($total_worked_hours, 2) . ' hrs</td></tr>
            <tr><td style="color:#888;">Required Hours</td><td align="right" style="color:#888;">' . ($uses_fallback_rate ? 'Not configured' : number_format($total_required_hours, 2) . ' hrs') . '</td></tr>
            <tr><td style="color:#b00;">SSS Deduction</td><td align="right" style="color:#b00;">&#8369;' . number_format($sss_deduction, 2) . '</td></tr>
            <tr>
                <td style="border-top:2px solid #555;"><strong>Net Pay</strong></td>
                <td align="right" style="border-top:2px solid #555;"><strong>&#8369;' . number_format($net_pay, 2) . '</strong></td>
            </tr>
        </tbody>
    </table>
    <div style="margin-top:2px;font-size:5.5pt;">
        <strong>Notes:</strong>
        <span>Salary based on actual hours worked. Contact HR for discrepancies.</span>
    </div>

    <!-- Signature Lines (compact) -->
    <div style="margin-top:5px; font-size:5.5pt;">
        <table style="width:100%;">
            <tr>
                <td style="width:48%; text-align:center;">
                    <div style="border-top:1px dashed #888; padding-top:1px;">Employee</div>
                </td>
                <td style="width:4%;"></td>
                <td style="width:48%; text-align:center;">
                    <div style="border-top:1px dashed #888; padding-top:1px;">HR/Employer</div>
                </td>
            </tr>
        </table>
    </div>

    <div style="text-align:center;font-size:4.5pt;color:#888;margin-top:1px;">System-generated payslip. No signature required.</div>
    ';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('Payslip_' . $selected_month . '.pdf', 'I');
    exit;
}
?>
