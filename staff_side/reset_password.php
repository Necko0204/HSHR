<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/security.php';
hshr_start_session('staff_public_session', '/staff_side/');

$selector = strtolower(trim((string) ($_POST['selector'] ?? $_GET['selector'] ?? '')));
$token = strtolower(trim((string) ($_POST['token'] ?? $_GET['token'] ?? '')));
$notice = null;
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = (string) ($_POST['password'] ?? '');
    $confirmation = (string) ($_POST['password_confirmation'] ?? '');
    if (!hshr_validate_csrf()) {
        $notice = 'Refresh the page and try again.';
    } elseif (!hshr_rate_limit_consume('staff-password-reset', $selector, 5, 1800)) {
        $notice = 'Too many attempts. Request a new reset link later.';
    } elseif (!preg_match('/^[a-f0-9]{32}$/', $selector) || !preg_match('/^[a-f0-9]{64}$/', $token)) {
        $notice = 'This password reset link is invalid.';
    } elseif (strlen($password) < 10 || strlen($password) > 200 || $password !== $confirmation) {
        $notice = 'Use at least 10 characters and enter the same password twice.';
    } else {
        try {
            require __DIR__ . '/db_config.php';
            $conn->begin_transaction();
            $lookup = $conn->prepare(
                'SELECT account_id, token_hash FROM staff_password_resets
                 WHERE selector = ? AND used_at IS NULL AND expires_at > NOW() LIMIT 1 FOR UPDATE'
            );
            $lookup->bind_param('s', $selector);
            $lookup->execute();
            $reset = $lookup->get_result()->fetch_assoc();
            $lookup->close();
            if (!$reset || !hash_equals((string) $reset['token_hash'], hash('sha256', $token))) {
                $conn->rollback();
                $notice = 'This password reset link is invalid or has expired.';
            } else {
                $accountId = (int) $reset['account_id'];
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $update = $conn->prepare('UPDATE staff_accounts SET password = ? WHERE id = ?');
                $update->bind_param('si', $passwordHash, $accountId);
                $update->execute();
                $update->close();
                $consume = $conn->prepare('UPDATE staff_password_resets SET used_at = NOW() WHERE selector = ?');
                $consume->bind_param('s', $selector);
                $consume->execute();
                $consume->close();
                $conn->commit();
                $success = true;
                $notice = 'Your password has been changed. You can now sign in.';
            }
        } catch (Throwable $error) {
            if (isset($conn) && $conn instanceof mysqli) {
                try { $conn->rollback(); } catch (Throwable $ignored) {}
            }
            error_log('Staff password reset failed: ' . $error->getMessage());
            $notice = 'The password could not be changed. Request a new reset link and try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose New Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height:100vh; display:flex; justify-content:center; align-items:center; background:linear-gradient(135deg,#4b0000,black); color:white; font-family:Poppins,sans-serif; }
        .reset-card { width:min(500px,calc(100% - 2rem)); background:rgba(255,255,255,.16); border-radius:12px; padding:2rem; box-shadow:0 4px 18px rgba(0,0,0,.35); }
        .btn-primary { background-color:#8b0000; border:0; }
    </style>
</head>
<body>
<main class="reset-card">
    <h1 class="h3 text-center">Choose New Password</h1>
    <?php if ($notice !== null): ?><div class="alert <?= $success ? 'alert-success' : 'alert-danger' ?>" role="status"><?= htmlspecialchars($notice, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
    <?php if (!$success): ?>
        <form method="post" action="reset_password.php">
            <?= hshr_csrf_field() ?>
            <input type="hidden" name="selector" value="<?= htmlspecialchars($selector, ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">
            <div class="mb-3"><label class="form-label" for="password">New Password</label><input class="form-control" type="password" id="password" name="password" minlength="10" maxlength="200" autocomplete="new-password" required></div>
            <div class="mb-3"><label class="form-label" for="password_confirmation">Confirm Password</label><input class="form-control" type="password" id="password_confirmation" name="password_confirmation" minlength="10" maxlength="200" autocomplete="new-password" required></div>
            <button type="submit" class="btn btn-primary w-100">Change Password</button>
        </form>
    <?php endif; ?>
    <div class="text-center mt-3"><a href="index.php" class="text-white">Back to Login</a></div>
</main>
</body>
</html>
