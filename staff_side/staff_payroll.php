<?php
require_once __DIR__ . '/includes/staff_session.php';

include 'db_config.php';
include 'staff_helper.php';

// 1) AUTH
if (!isset($_SESSION['employee_id']) || !in_array(strtolower($_SESSION['role'] ?? ''), ['staff', 'intern'], true)) {
    header("Location: index.php");
    exit();
}
$employee_id = $_SESSION['employee_id'];

// 2) FETCH monthly salary from employees.salary
$stmt = $conn->prepare("SELECT salary FROM employees WHERE id = ?");
$stmt->bind_param("s", $employee_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$monthly_salary = floatval($res['salary'] ?? 0);
$stmt->close();

// 3) FETCH weekly required hours from work_schedules
$stmt = $conn->prepare("
    SELECT COALESCE(SUM(required_hours),0) AS weekly_required
    FROM work_schedules
    WHERE employee_id = ?
");
$stmt->bind_param("s", $employee_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$weekly_required = floatval($res['weekly_required']);
$stmt->close();

// 4) CALCULATE monthly required hours & hourly rate
$monthly_required_hours = $weekly_required * 4;             // approx 4 weeks/month
if ($monthly_required_hours > 0 && $monthly_salary > 0) {
    $hourly_rate = $monthly_salary / $monthly_required_hours;
} else {
    $hourly_rate = 50;  // fallback ₱50/hr
}

// 5) PULL attendance and build payroll_data
$query = "
  SELECT
    DATE_FORMAT(date, '%M %Y') AS month,
    SEC_TO_TIME(SUM(
      COALESCE(
        TIME_TO_SEC(total_hours),
        GREATEST(
          TIME_TO_SEC(TIMEDIFF(time_out, time_in)) - COALESCE(TIME_TO_SEC(break_duration), 0),
          0
        )
      )
    )) AS total_hours
  FROM attendance
  WHERE employee_id = ? AND time_in IS NOT NULL AND time_out IS NOT NULL
  GROUP BY DATE_FORMAT(date, '%Y-%m')
  ORDER BY MIN(date) DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

$payroll_data = [];
while ($row = $result->fetch_assoc()) {
    $month = $row['month'];
    $hms   = $row['total_hours'] ?? '00:00:00';
    list($H, $M, $S) = explode(':', $hms);
    $decimal_hours = $H + ($M/60) + ($S/3600);

    // COMPUTE pay using dynamic hourly_rate
    $computed_salary = $decimal_hours * $hourly_rate;

    $payroll_data[] = [
      'month'            => $month,
      'total_hours'      => $hms,
      'computed_salary'  => $computed_salary,
    ];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../images/asdasdasd123123123123123.jpg">
    <title>Payroll</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body   >



<!-- Back to Dashboard Button -->
<div style="position: absolute; top: 7px; left: 20px; z-index: 1000;">
    <a href="dashboard.php" class="btn btn-secondary">
        <i class="fa fa-arrow-left"></i> Back to Dashboard
    </a>
</div>
    <!-- Navigation Bar -->
    <div class="main-container">
        <?php include 'staff_navbar.php'; ?>
    </div>


     <!-- Animated Box Shapes -->
<!-- Animated Box Shapes -->
<div class="animation-container">
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
</div>


<main class="dashboard-container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-lg border-0 rounded-4 w-100" style="max-width: 100%; background: rgba(255,255,255,0.97);">
        <div class="card-header text-center rounded-top-4" style="background: linear-gradient(90deg, #f8fafc 0%, #e9ecef 100%); border-bottom: none;">
            <h2 class="mb-0" style="font-weight: 700; letter-spacing: 1px; color: #333;">Monthly Payroll Summary</h2>
            <small class="text-muted">Dates and attendance cutoffs use Philippine Standard Time (GMT+8).</small>
        </div>
        <div class="card-body p-4">

            <?php
            $months = [];
            $query = $conn->prepare("SELECT DISTINCT DATE_FORMAT(date, '%Y-%m') AS month FROM attendance WHERE employee_id = ? ORDER BY month DESC");
            $query->bind_param("s", $employee_id);
            $query->execute();
            $result = $query->get_result();
            while ($row = $result->fetch_assoc()) {
                $months[] = $row['month'];
            }
            $query->close();
            ?>

            <!-- Payslip Print Form (centered, with spacing) -->
            <div class="d-flex justify-content-center mb-4">
                <form method="get" action="print_payslip.php" target="_blank" class="d-flex flex-row gap-2 align-items-center" style="max-width: 400px;">
                    <select name="month" class="form-select" required style="min-width: 160px;">
                        <option value="" disabled selected>Select Month</option>
                        <?php foreach ($months as $month): ?>
                            <option value="<?= htmlspecialchars($month) ?>"><?= htmlspecialchars($month) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="action" value="print_payslip">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-printer"></i> Print Payslip
                    </button>
                </form>
            </div>

            <table class="table table-hover align-middle mb-0">
                <thead style="background: #f1f3f6;">
                    <tr>
                        <th style="width: 35%;">Month</th>
                        <th style="width: 35%;">Total Hours Worked</th>
                        <th style="width: 30%;">Computed Salary (₱)</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($payroll_data)) : ?>
                    <?php foreach ($payroll_data as $row) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['month']) ?></td>
                        <td>
                            <span class="badge bg-light text-dark fs-6 px-3 py-2 border" style="font-size:1.1rem;"><?= htmlspecialchars($row['total_hours']) ?></span>
                        </td>
                        <td>
                            <span class="fw-bold text-success fs-5">₱<?= number_format($row['computed_salary'], 2) ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">
                            <i class="bi bi-emoji-frown fs-3"></i><br>
                            No payroll records found.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-light text-end rounded-bottom-4 px-4 py-3" style="border-top: none;">
            <span class="text-muted" style="font-size: 0.95rem;">
                Hourly Rate: <span class="fw-semibold text-primary">₱<?= number_format($hourly_rate, 2) ?></span>
            </span>
        </div>
    </div>
</main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
