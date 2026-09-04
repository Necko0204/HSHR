<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/staff_session.php';

if (!empty($_SESSION['employee_id'])) {
    header('Location: dashboard.php');
    exit;
}

$loginCssVersion = (string) filemtime(__DIR__ . '/../assets/css/staff-login.css');
$loginJsVersion = (string) filemtime(__DIR__ . '/../assets/js/staff-login.js');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#5b1027">
    <meta name="description" content="Secure employee access for Holy Spirit School of Imus Human Resources.">
    <link rel="icon" type="image/jpeg" href="../images/asdasdasd123123123123123.jpg">
    <title>Staff Portal · Holy Spirit Human Resources</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/staff-login.css?v=<?= rawurlencode($loginCssVersion) ?>">
</head>
<body class="staff-login-page">
    <main class="staff-login-shell">
        <section class="staff-login-story" aria-labelledby="portalStoryTitle">
            <div class="story-pattern" aria-hidden="true"></div>

            <header class="staff-login-brand">
                <img src="../images/asdasdasd123123123123123.jpg" alt="Holy Spirit School of Imus seal">
                <div>
                    <strong>Holy Spirit School of Imus</strong>
                    <span>Human Resources</span>
                </div>
            </header>

            <div class="story-content">
                <span class="story-eyebrow"><i class="fa-solid fa-sparkles" aria-hidden="true"></i> Employee workspace</span>
                <h1 id="portalStoryTitle">Your workday,<br><em>in one place.</em></h1>
                <p>Access attendance, schedules, leave requests, payroll, and staff services through one secure portal.</p>

                <div class="portal-services" aria-label="Available staff services">
                    <span><i class="fa-solid fa-calendar-check" aria-hidden="true"></i> Attendance</span>
                    <span><i class="fa-solid fa-wallet" aria-hidden="true"></i> Payroll</span>
                    <span><i class="fa-solid fa-person-walking-arrow-right" aria-hidden="true"></i> Leave</span>
                </div>
            </div>

            <footer class="story-footer">
                <div><span class="status-dot" aria-hidden="true"></span><strong>System operational</strong></div>
                <span>Philippine Standard Time · GMT+8</span>
            </footer>
        </section>

        <section class="staff-login-access" aria-labelledby="loginTitle">
            <div class="mobile-brand">
                <img src="../images/asdasdasd123123123123123.jpg" alt="">
                <div><strong>Holy Spirit</strong><span>Staff Portal</span></div>
            </div>

            <div class="access-card">
                <header class="access-heading">
                    <span class="access-icon" aria-hidden="true"><i class="fa-solid fa-user-shield"></i></span>
                    <span class="access-kicker">Secure staff access</span>
                    <h2 id="loginTitle">Welcome back</h2>
                    <p>Sign in with your staff account to continue.</p>
                </header>

                <div class="login-alert" data-login-alert role="alert" aria-live="polite" hidden></div>

                <form id="loginForm" class="staff-login-form" method="POST" action="login_logic.php" novalidate>
                    <?= hshr_csrf_field() ?>
                    <div hidden aria-hidden="true"><label for="website">Website</label><input type="text" id="website" name="website" tabindex="-1" autocomplete="off"></div>
                    <label class="field-group" for="username">
                        <span>Username</span>
                        <span class="field-control">
                            <i class="fa-regular fa-user" aria-hidden="true"></i>
                            <input type="text" id="username" name="username" placeholder="Enter your username" autocomplete="username" autocapitalize="none" spellcheck="false" required>
                        </span>
                        <small data-field-error="username"></small>
                    </label>

                    <label class="field-group" for="password">
                        <span>Password</span>
                        <span class="field-control">
                            <i class="fa-solid fa-lock" aria-hidden="true"></i>
                            <input type="password" id="password" name="password" placeholder="Enter your password" autocomplete="current-password" required>
                            <button type="button" class="password-toggle" data-password-toggle aria-label="Show password" aria-pressed="false">
                                <i class="fa-regular fa-eye" aria-hidden="true"></i>
                            </button>
                        </span>
                        <small data-field-error="password"></small>
                        <small class="caps-lock-note" data-caps-lock hidden><i class="fa-solid fa-arrow-up" aria-hidden="true"></i> Caps Lock is on</small>
                    </label>

                    <div class="form-options">
                        <label class="remember-option"><input type="checkbox" name="remember_username" value="1" data-remember-username><span aria-hidden="true"></span> Remember username</label>
                        <a href="forgot_password.php">Forgot password?</a>
                    </div>

                    <button type="submit" class="staff-login-button" data-login-button>
                        <span data-button-label>Sign in to staff portal</span>
                        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        <i class="fa-solid fa-circle-notch fa-spin button-spinner" aria-hidden="true"></i>
                    </button>
                </form>

                <div class="security-note"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i><span><strong>Protected access</strong>Your session is isolated from administrator accounts.</span></div>
            </div>

            <footer class="access-footer">
                <span>© <?= date('Y') ?> Holy Spirit School of Imus, Inc.</span>
                <span>Authorized staff only</span>
            </footer>
        </section>
    </main>

    <script src="../assets/js/staff-login.js?v=<?= rawurlencode($loginJsVersion) ?>" defer></script>
</body>
</html>
