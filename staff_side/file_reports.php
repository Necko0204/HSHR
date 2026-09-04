<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/staff_session.php';
if (empty($_SESSION['employee_id']) || !in_array(strtolower((string) ($_SESSION['role'] ?? '')), ['staff', 'intern'], true)) {
    header('Location: index.php');
    exit;
}
require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/staff_helper.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="../images/asdasdasd123123123123123.jpg">
    <title>Reports · HSSII Employee Portal</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/staff_navbar.php'; ?>
    <main class="hshr-staff-page">
        <section class="hshr-staff-page-hero">
            <div><span class="hshr-staff-eyebrow">Employee support</span><h1>Reports & concerns</h1><p>Document workplace incidents securely so the HR team can review and follow up.</p></div>
            <div class="hshr-staff-page-icon"><i class="fa-regular fa-file-lines"></i></div>
        </section>
        <section class="hshr-staff-service-grid">
            <a href="incident_report.php" class="hshr-staff-service-card">
                <span><i class="fa-solid fa-triangle-exclamation"></i></span>
                <div><small>Workplace documentation</small><h2>File an incident report</h2><p>Record the people, location, impact, and actions connected to an incident.</p></div>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
            <article class="hshr-staff-report-note">
                <i class="fa-solid fa-shield-halved"></i>
                <div><strong>Provide clear, factual details</strong><p>Include only information relevant to the incident. HR will review your submission and coordinate any necessary follow-up.</p></div>
            </article>
        </section>
    </main>
</body>
</html>
