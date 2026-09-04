<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/admin_session.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$adminLoginCssVersion = (string) filemtime(__DIR__ . '/assets/css/admin-login.css');
$adminLoginJsVersion = (string) filemtime(__DIR__ . '/assets/js/admin-login.js');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#09090b">
    <meta name="description" content="Secure administrator access for Holy Spirit School of Imus Human Resources.">
    <link rel="icon" type="image/jpeg" href="images/asdasdasd123123123123123.jpg">
    <title>Administrator Access · Holy Spirit Human Resources</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin-login.css?v=<?= rawurlencode($adminLoginCssVersion) ?>">
</head>
<body class="admin-login-page">
    <main class="admin-login-shell">
        <section class="admin-login-vision" aria-labelledby="adminVisionTitle">
            <div class="vision-grid" aria-hidden="true"></div>
            <svg class="vision-network" viewBox="0 0 1000 900" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                <g class="network-lines">
                    <path d="M40 155 L190 86 L315 190 L475 105 L610 214 L790 95 L950 180"/>
                    <path d="M70 390 L220 275 L365 370 L520 265 L690 390 L845 275 L970 420"/>
                    <path d="M20 650 L180 540 L330 675 L485 535 L650 690 L805 545 L980 660"/>
                    <path d="M190 86 L220 275 L180 540 M315 190 L365 370 L330 675 M475 105 L520 265 L485 535 M610 214 L690 390 L650 690 M790 95 L845 275 L805 545"/>
                    <path d="M40 155 L220 275 L315 190 L520 265 L610 214 L845 275 L950 180 M70 390 L180 540 L365 370 L485 535 L690 390 L805 545 L970 420"/>
                </g>
                <g class="network-nodes">
                    <circle cx="40" cy="155" r="5"/><circle cx="190" cy="86" r="7"/><circle cx="315" cy="190" r="5"/><circle cx="475" cy="105" r="6"/><circle cx="610" cy="214" r="5"/><circle cx="790" cy="95" r="7"/><circle cx="950" cy="180" r="5"/>
                    <circle cx="70" cy="390" r="6"/><circle cx="220" cy="275" r="5"/><circle cx="365" cy="370" r="7"/><circle cx="520" cy="265" r="5"/><circle cx="690" cy="390" r="6"/><circle cx="845" cy="275" r="5"/><circle cx="970" cy="420" r="7"/>
                    <circle cx="20" cy="650" r="5"/><circle cx="180" cy="540" r="7"/><circle cx="330" cy="675" r="5"/><circle cx="485" cy="535" r="6"/><circle cx="650" cy="690" r="7"/><circle cx="805" cy="545" r="5"/><circle cx="980" cy="660" r="6"/>
                </g>
            </svg>

            <header class="admin-vision-brand">
                <img src="images/asdasdasd123123123123123.jpg" alt="Holy Spirit School of Imus seal">
                <div><strong>Holy Spirit School of Imus</strong><span>Human Resources</span></div>
            </header>

            <div class="vision-content">
                <span class="vision-eyebrow"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i> Administrative command center</span>
                <h1 id="adminVisionTitle">People operations,<br><em>connected.</em></h1>
                <p>One secure workspace for workforce planning, employee records, attendance, payroll, and institutional decisions.</p>
                <div class="vision-metrics" aria-label="Platform capabilities">
                    <span><strong>01</strong>Unified records</span>
                    <span><strong>02</strong>Live operations</span>
                    <span><strong>03</strong>Secure access</span>
                </div>
            </div>

            <footer class="vision-footer">
                <div><span class="system-dot" aria-hidden="true"></span>Local HR system operational</div>
                <span>HSSII HR · <?= date('Y') ?></span>
            </footer>
        </section>

        <section class="admin-login-access" aria-labelledby="adminLoginTitle">
            <div class="admin-mobile-brand">
                <img src="images/asdasdasd123123123123123.jpg" alt="">
                <div><strong>Holy Spirit</strong><span>Administrator Portal</span></div>
            </div>

            <div class="admin-access-card">
                <header class="admin-access-heading">
                    <span class="admin-access-icon" aria-hidden="true"><i class="fa-solid fa-key"></i></span>
                    <span class="admin-access-kicker">Administrator access</span>
                    <h2 id="adminLoginTitle">Sign in securely</h2>
                    <p>Use your authorized administrator account to continue.</p>
                </header>

                <div class="admin-login-alert" data-admin-login-alert role="alert" aria-live="polite" hidden></div>

                <form id="adminLoginForm" class="admin-login-form" method="POST" action="login_logic.php" novalidate>
                    <?= hshr_csrf_field() ?>
                    <div class="honeypot-field" aria-hidden="true" hidden>
                        <label for="website">Website</label><input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <label class="admin-field" for="username">
                        <span>Username</span>
                        <span class="admin-field-control">
                            <i class="fa-regular fa-user" aria-hidden="true"></i>
                            <input type="text" id="username" name="username" placeholder="Enter your username" autocomplete="username" autocapitalize="none" spellcheck="false" required>
                        </span>
                        <small data-admin-field-error="username"></small>
                    </label>

                    <label class="admin-field" for="password">
                        <span>Password</span>
                        <span class="admin-field-control">
                            <i class="fa-solid fa-lock" aria-hidden="true"></i>
                            <input type="password" id="password" name="password" placeholder="Enter your password" autocomplete="current-password" required>
                            <button type="button" class="admin-password-toggle" data-admin-password-toggle aria-label="Show password" aria-pressed="false"><i class="fa-regular fa-eye" aria-hidden="true"></i></button>
                        </span>
                        <small data-admin-field-error="password"></small>
                        <small class="admin-caps-note" data-admin-caps-lock hidden><i class="fa-solid fa-arrow-up" aria-hidden="true"></i> Caps Lock is on</small>
                    </label>

                    <div class="admin-form-options">
                        <label class="admin-remember"><input type="checkbox" name="remember_username" value="1" data-admin-remember><span aria-hidden="true"></span> Remember username</label>
                        <button type="button" data-admin-recovery>Forgot password?</button>
                    </div>

                    <button type="submit" class="admin-login-button" data-admin-login-button>
                        <span data-admin-button-label>Continue to dashboard</span>
                        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        <i class="fa-solid fa-circle-notch fa-spin admin-button-spinner" aria-hidden="true"></i>
                    </button>

                    <div class="admin-divider"><span>or continue with</span></div>

                    <button type="button" class="admin-google-button" data-admin-google>
                        <img src="images/google-logo-9825.png" alt="">
                        Google Workspace
                    </button>
                </form>

                <div class="admin-security-note"><i class="fa-solid fa-lock" aria-hidden="true"></i><span><strong>Restricted system</strong>Administrator activity is session-protected and separated from staff access.</span></div>
            </div>

            <footer class="admin-access-footer"><span>© <?= date('Y') ?> Holy Spirit School of Imus, Inc.</span><span>Authorized administrators only</span></footer>
        </section>
    </main>

    <script src="assets/js/admin-login.js?v=<?= rawurlencode($adminLoginJsVersion) ?>" defer></script>
</body>
</html>
