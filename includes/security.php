<?php
declare(strict_types=1);

/**
 * Shared request, session, CSRF, password, and lightweight rate-limit helpers.
 * This file deliberately has no database dependency so it is safe to load from
 * public pages, authentication endpoints, and both application portals.
 */

function hshr_is_https(): bool
{
    if (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off') {
        return true;
    }

    if (getenv('TRUST_PROXY_HEADERS') === '1') {
        $forwardedProto = strtolower(trim(explode(',', (string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''))[0]));
        return $forwardedProto === 'https';
    }

    return false;
}

function hshr_security_headers(): void
{
    if (headers_sent()) {
        return;
    }

    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(self), microphone=(), geolocation=()');
    header("Content-Security-Policy: base-uri 'self'; frame-ancestors 'self'; object-src 'none'; form-action 'self'");
}

function hshr_start_session(string $name, string $path): void
{
    hshr_security_headers();

    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');

    session_name($name);
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => $path,
        'secure' => hshr_is_https(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function hshr_clear_session(string $path): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        setcookie(session_name(), '', [
            'expires' => time() - 42000,
            'path' => $path,
            'secure' => hshr_is_https(),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }
    session_destroy();
}

function hshr_enforce_idle_timeout(string $identityKey): void
{
    if (empty($_SESSION[$identityKey])) {
        return;
    }

    $configured = filter_var(getenv('AUTH_IDLE_TIMEOUT_SECONDS'), FILTER_VALIDATE_INT);
    $timeout = $configured !== false && $configured >= 300 ? $configured : 3600;
    $now = time();
    $lastActivity = (int) ($_SESSION['_last_activity'] ?? $now);

    if (($now - $lastActivity) > $timeout) {
        $_SESSION = [];
        session_regenerate_id(true);
        return;
    }

    $fingerprint = hash('sha256', (string) ($_SERVER['HTTP_USER_AGENT'] ?? 'unknown'));
    if (isset($_SESSION['_user_agent']) && !hash_equals((string) $_SESSION['_user_agent'], $fingerprint)) {
        $_SESSION = [];
        session_regenerate_id(true);
        return;
    }

    $_SESSION['_user_agent'] = $fingerprint;
    $_SESSION['_last_activity'] = $now;
}

function hshr_mark_authenticated_session(): void
{
    $_SESSION['_last_activity'] = time();
    $_SESSION['_user_agent'] = hash('sha256', (string) ($_SERVER['HTTP_USER_AGENT'] ?? 'unknown'));
}

function hshr_csrf_token(): string
{
    if (empty($_SESSION['_csrf_token']) || !is_string($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf_token'];
}

function hshr_csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . htmlspecialchars(hshr_csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

function hshr_validate_csrf(): bool
{
    $provided = (string) ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($_POST['_csrf'] ?? ''));
    $expected = (string) ($_SESSION['_csrf_token'] ?? '');

    return $expected !== '' && $provided !== '' && hash_equals($expected, $provided);
}

function hshr_require_csrf_json(): void
{
    if (hshr_validate_csrf()) {
        return;
    }

    http_response_code(403);
    echo json_encode(['success' => false, 'status' => 'error', 'error' => 'Invalid security token', 'message' => 'Refresh the page and try again.']);
    exit;
}

function hshr_verify_password(string $plainText, string $storedHash): bool
{
    $passwordInfo = password_get_info($storedHash);
    if (($passwordInfo['algoName'] ?? 'unknown') !== 'unknown') {
        return password_verify($plainText, $storedHash);
    }

    // Compatibility path for the repository's historical SHA-256 records.
    return preg_match('/^[a-f0-9]{64}$/i', $storedHash) === 1
        && hash_equals(strtolower($storedHash), hash('sha256', $plainText));
}

function hshr_password_needs_upgrade(string $storedHash): bool
{
    $passwordInfo = password_get_info($storedHash);
    return ($passwordInfo['algoName'] ?? 'unknown') === 'unknown'
        || password_needs_rehash($storedHash, PASSWORD_DEFAULT);
}

function hshr_profile_picture_url(?string $storedPath, string $relativePrefix = ''): string
{
    $fallback = $relativePrefix . 'images/image-not-found.jpg';
    $normalized = ltrim(str_replace('\\', '/', trim((string) $storedPath)), '/');

    // Profile images are always flat files in the dedicated upload directory.
    if (preg_match('~^uploads/profile_pictures/[A-Za-z0-9._-]+$~', $normalized) !== 1) {
        return $fallback;
    }

    $diskPath = dirname(__DIR__) . DIRECTORY_SEPARATOR
        . str_replace('/', DIRECTORY_SEPARATOR, $normalized);
    if (!is_file($diskPath)) {
        return $fallback;
    }

    return $relativePrefix . $normalized;
}

function hshr_client_ip(): string
{
    // REMOTE_ADDR is intentionally used unless a deployment explicitly opts in
    // to trusted proxy headers; accepting arbitrary forwarding headers permits
    // trivial rate-limit bypasses.
    if (getenv('TRUST_PROXY_HEADERS') === '1') {
        $forwarded = trim(explode(',', (string) ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? ''))[0]);
        if (filter_var($forwarded, FILTER_VALIDATE_IP)) {
            return $forwarded;
        }
    }

    return (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
}

function hshr_rate_limit_consume(string $scope, string $identifier, int $limit, int $windowSeconds): bool
{
    $key = hash('sha256', $scope . '|' . hshr_client_ip() . '|' . strtolower(trim($identifier)));
    $path = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'hshr-rate-' . $key . '.json';
    $handle = @fopen($path, 'c+');
    if ($handle === false) {
        // Availability wins if the runtime cannot write its temporary directory.
        return true;
    }

    try {
        if (!flock($handle, LOCK_EX)) {
            return true;
        }

        $raw = stream_get_contents($handle);
        $record = json_decode($raw ?: '', true);
        $now = time();
        if (!is_array($record) || ($now - (int) ($record['started'] ?? 0)) >= $windowSeconds) {
            $record = ['started' => $now, 'count' => 0];
        }

        if ((int) $record['count'] >= $limit) {
            return false;
        }

        $record['count'] = (int) $record['count'] + 1;
        rewind($handle);
        ftruncate($handle, 0);
        fwrite($handle, json_encode($record, JSON_THROW_ON_ERROR));
        fflush($handle);
        return true;
    } catch (Throwable $error) {
        error_log('Rate-limit state could not be updated: ' . $error->getMessage());
        return true;
    } finally {
        @flock($handle, LOCK_UN);
        fclose($handle);
    }
}

function hshr_rate_limit_reset(string $scope, string $identifier): void
{
    $key = hash('sha256', $scope . '|' . hshr_client_ip() . '|' . strtolower(trim($identifier)));
    $path = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'hshr-rate-' . $key . '.json';
    if (is_file($path)) {
        @unlink($path);
    }
}
