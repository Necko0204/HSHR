<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/mailer.php';
hshr_start_session('staff_public_session', '/staff_side/');

$notice = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $validRequest = hshr_validate_csrf()
        && (string) ($_POST['website'] ?? '') === ''
        && filter_var($email, FILTER_VALIDATE_EMAIL)
        && hshr_rate_limit_consume('staff-password-request', $email, 3, 3600);

    if ($validRequest) {
        try {
            require __DIR__ . '/db_config.php';
            $lookup = $conn->prepare(
                "SELECT sa.id, e.firstname, e.lastname FROM staff_accounts sa
                 JOIN employees e ON e.id = sa.employee_id
                 WHERE LOWER(e.email1) = ? AND LOWER(sa.status) = 'active' AND LOWER(e.status) = 'active' LIMIT 1"
            );
            $lookup->bind_param('s', $email);
            $lookup->execute();
            $account = $lookup->get_result()->fetch_assoc();
            $lookup->close();

            if ($account) {
                $selector = bin2hex(random_bytes(16));
                $validator = bin2hex(random_bytes(32));
                $tokenHash = hash('sha256', $validator);
                $accountId = (int) $account['id'];

                $conn->begin_transaction();
                $delete = $conn->prepare('DELETE FROM staff_password_resets WHERE account_id = ? OR expires_at < NOW()');
                $delete->bind_param('i', $accountId);
                $delete->execute();
                $delete->close();
                $insert = $conn->prepare(
                    'INSERT INTO staff_password_resets (selector, account_id, token_hash, expires_at)
                     VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 30 MINUTE))'
                );
                $insert->bind_param('sis', $selector, $accountId, $tokenHash);
                $insert->execute();
                $insert->close();
                $conn->commit();

                $resetUrl = hshr_application_base_url() . '/staff_side/reset_password.php?selector='
                    . rawurlencode($selector) . '&token=' . rawurlencode($validator);
                $mailer = new PHPMailer(true);
                hshr_configure_mailer($mailer);
                $name = trim((string) ($account['firstname'] . ' ' . $account['lastname']));
                $mailer->addAddress($email, $name);
                $mailer->isHTML(true);
                $mailer->Subject = 'Reset your staff portal password';
                $safeName = htmlspecialchars((string) $account['firstname'], ENT_QUOTES, 'UTF-8');
                $safeUrl = htmlspecialchars($resetUrl, ENT_QUOTES, 'UTF-8');
                $mailer->Body = "<p>Hello {$safeName},</p><p><a href=\"{$safeUrl}\">Reset your staff portal password</a>. This link expires in 30 minutes and can be used once.</p><p>If you did not request this, ignore this email.</p>";
                $mailer->AltBody = "Reset your staff portal password within 30 minutes: {$resetUrl}";
                $mailer->send();
            }
        } catch (Throwable $error) {
            if (isset($conn) && $conn instanceof mysqli) {
                try { $conn->rollback(); } catch (Throwable $ignored) {}
            }
            error_log('Staff password reset request failed: ' . $error->getMessage());
        }
    }
    $notice = 'If an active account matches that email, a one-time reset link has been sent.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height:100vh; display:flex; justify-content:center; align-items:center; background:linear-gradient(135deg,#4b0000,black); color:white; font-family:Poppins,sans-serif; }
        .reset-card { width:min(500px,calc(100% - 2rem)); background:rgba(255,255,255,.16); border-radius:12px; padding:2rem; box-shadow:0 4px 18px rgba(0,0,0,.35); }
        .btn-primary { background-color:#8b0000; border:0; } .btn-primary:hover { background-color:#660000; }
    </style>
</head>
<body>
<main class="reset-card">
    <h1 class="h3 text-center">Reset Password</h1>
    <p class="text-center">Enter the email associated with your staff account.</p>
    <?php if ($notice !== null): ?><div class="alert alert-info" role="status"><?= htmlspecialchars($notice, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
    <form method="post" action="forgot_password.php">
        <?= hshr_csrf_field() ?>
        <input type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true" style="position:absolute;left:-10000px">
        <div class="mb-3"><label for="email" class="form-label">Email Address</label><input type="email" class="form-control" id="email" name="email" maxlength="100" autocomplete="email" required></div>
        <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
    </form>
    <div class="text-center mt-3"><a href="index.php" class="text-white">Back to Login</a></div>
</main>
</body>
</html>
