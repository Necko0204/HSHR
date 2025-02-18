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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
        <main class="wrapper">
                <!-- Page Heading -->
                <h1 >HR Reports</h1>
                <!-- Employee Attendance Report -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Employee Attendance Report</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Purpose:</strong> To track employee attendance and identify absenteeism trends.</p>
                        <ul>
                            <li>Employee ID, Name, Department</li>
                            <li>Dates of attendance, late arrivals, early departures</li>
                            <li>Total days worked, total days absent, and reason for absence (sick leave, personal leave, etc.)</li>
                            <li>Absenteeism rate by department or team</li>
                            <li>Overtime worked and adjustments</li>
                            <li>Trends over a specific period (monthly, quarterly, or yearly)</li>
                            <li>Comparison with previous periods (e.g., absenteeism trends year-over-year)</li>
                        </ul>
                    </div>
                </div>
                <!-- Payroll Report -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Payroll Report</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Purpose:</strong> To track payroll details for employees, including their compensation and deductions.</p>
                        <ul>
                            <li>Employee ID, Name, Role, Department</li>
                            <li>Basic Salary, Bonuses, and Overtime pay</li>
                            <li>Deductions (e.g., taxes, insurance premiums, retirement contributions)</li>
                            <li>Net Pay</li>
                            <li>Salary history over a specific period (monthly, quarterly)</li>
                            <li>Payment status (paid/unpaid) and pending payments</li>
                            <li>Tax contributions (federal, state, or local taxes)</li>
                            <li>Benefits summary (health insurance, retirement plans, etc.)</li>
                            <li>Salary comparison across departments or roles</li>
                        </ul>
                    </div>
                </div>
                <!-- Leave Management Report -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Leave Management Report</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Purpose:</strong> To track employee leave balances, usage, and trends.</p>
                        <ul>
                            <li>Employee ID, Name, Role, Department</li>
                            <li>Leave types (sick, vacation, personal, parental, etc.)</li>
                            <li>Leave balance for each type of leave</li>
                            <li>Leave requests (approved, pending, or denied)</li>
                            <li>Total leave used within a specific period</li>
                            <li>Trends of leave usage by department, gender, or role</li>
                            <li>Leave abuse or patterns (e.g., frequent sick days)</li>
                            <li>Leave carried over or forfeited based on company policies</li>
                        </ul>
                    </div>
                </div>
                <!-- Employee Performance Report -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Employee Performance Report</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Purpose:</strong> To assess individual or team performance over a specific period.</p>
                        <ul>
                            <li>Employee ID, Name, Department, Role</li>
                            <li>Performance scores from annual or quarterly evaluations</li>
                            <li>Strengths and weaknesses noted by managers</li>
                            <li>Training programs attended and certifications earned</li>
                            <li>Achievement of set goals and targets (e.g., sales quotas, project deadlines)</li>
                            <li>Feedback and comments from managers or peers</li>
                            <li>Self-assessment vs manager evaluation</li>
                            <li>Comparison of performance between teams or departments</li>
                        </ul>
                    </div>
                </div>
                <!-- Recruitment & Hiring Report -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Recruitment & Hiring Report</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Purpose:</strong> To analyze the recruitment process and hiring trends.</p>
                        <ul>
                            <li>Job openings by department, role, or position</li>
                            <li>Number of candidates applied, interviewed, and hired</li>
                            <li>Time-to-hire and time-to-fill metrics</li>
                            <li>Recruitment source analysis (e.g., job boards, employee referrals, etc.)</li>
                            <li>Diversity statistics in hiring (gender, race, experience)</li>
                            <li>Candidate rejection reasons</li>
                            <li>Cost-per-hire breakdown (advertising costs, recruitment agency fees)</li>
                            <li>Successful onboarding rate and integration process</li>
                        </ul>
                    </div>
                </div>
                <!-- Training and Development Report -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Training and Development Report</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Purpose:</strong> To track training programs, employee development, and skills enhancement.</p>
                        <ul>
                            <li>Employee ID, Name, Department</li>
                            <li>Training programs attended, dates, and duration</li>
                            <li>Skill development achieved</li>
                            <li>Certifications completed (e.g., project management, software skills)</li>
                            <li>Training costs and expenses</li>
                            <li>Performance improvement post-training</li>
                            <li>Feedback and ratings from employees on training effectiveness</li>
                            <li>Comparison of training effectiveness between departments or roles</li>
                            <li>Employee participation rates in voluntary training programs</li>
                        </ul>
                    </div>
                </div>

                <!-- Employee Turnover Report -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Employee Turnover Report</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Purpose:</strong> To analyze employee attrition and identify trends.</p>
                        <ul>
                            <li>Employee ID, Name, Department, Role</li>
                            <li>Reason for leaving (voluntary resignation, retirement, involuntary separation, etc.)</li>
                            <li>Exit interview feedback (if available)</li>
                            <li>Length of service before departure</li>
                            <li>Turnover rate by department or role</li>
                            <li>Comparison with industry benchmarks for turnover</li>
                            <li>Impact on team performance and productivity due to turnover</li>
                            <li>Hiring cost vs turnover cost analysis</li>
                            <li>Retention strategies effectiveness (e.g., bonuses, employee engagement programs)</li>
                        </ul>
                    </div>
                </div>
                <!-- Employee Demographics Report -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Employee Demographics Report</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Purpose:</strong> To understand the diversity and structure of the workforce.</p>
                        <ul>
                            <li>Employee ID, Name, Age, Gender, Marital Status, etc.</li>
                            <li>Department, Role, Location</li>
                            <li>Tenure and career progression</li>
                            <li>Diversity breakdown (ethnicity, nationality, gender, age, etc.)</li>
                            <li>Salary distribution across demographics</li>
                            <li>Gender pay gap analysis</li>
                            <li>Employee satisfaction rates segmented by demographic categories</li>
                            <li>Career development opportunities for underrepresented groups</li>
                        </ul>
                    </div>
                </div>
                <!-- Compliance and Policy Adherence Report -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Compliance and Policy Adherence Report</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Purpose:</strong> To track adherence to labor laws and company policies.</p>
                        <ul>
                            <li>Compliance with labor laws (working hours, overtime, minimum wage, etc.)</li>
                            <li>Occupational health and safety incidents or violations</li>
                            <li>Disciplinary actions taken and their outcomes</li>
                            <li>Policy violations (e.g., attendance policy, dress code, code of conduct)</li>
                            <li>Legal claims or lawsuits filed against the company (if any)</li>
                            <li>Statutory reporting (tax, insurance, benefits contributions)</li>
                            <li>Audits conducted (internal or external) and their results</li>
                            <li>Action plans for improving compliance in specific areas</li>
                        </ul>
                    </div>
                </div>
                <!-- Compensation & Benefits Report -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Compensation & Benefits Report</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Purpose:</strong> To provide a breakdown of employee compensation and benefits packages.</p>
                        <ul>
                            <li>Employee ID, Name, Role, Department</li>
                            <li>Base salary, bonuses, incentives</li>
                            <li>Benefits package details (health insurance, retirement savings, paid leave, etc.)</li>
                            <li>Compensation analysis (base salary vs. industry standards)</li>
                            <li>Benefits participation rates</li>
                            <li>Employee feedback on compensation and benefits</li>
                            <li>Total cost of employee compensation and benefits</li>
                            <li>Impact of compensation changes on employee retention and satisfaction</li>
                        </ul>
                    </div>
                </div>
            </section>
        </main>
    <!-- Bootstrap JS -->
    <script src="background.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
