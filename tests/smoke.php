<?php
declare(strict_types=1);

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/includes/security.php';

function smokeCheck(bool $condition, string $message): void
{
    if (!$condition) throw new RuntimeException($message);
}

$modernHash = password_hash('Correct Horse Battery Staple', PASSWORD_DEFAULT);
smokeCheck(hshr_verify_password('Correct Horse Battery Staple', $modernHash), 'Modern password verification failed.');
smokeCheck(!hshr_verify_password('wrong', $modernHash), 'Modern password rejection failed.');

$legacyHash = hash('sha256', 'legacy-password');
smokeCheck(hshr_verify_password('legacy-password', $legacyHash), 'Legacy password compatibility failed.');
smokeCheck(hshr_password_needs_upgrade($legacyHash), 'Legacy password was not marked for upgrade.');

smokeCheck(class_exists(PHPMailer\PHPMailer\PHPMailer::class), 'PHPMailer is unavailable.');
smokeCheck(class_exists(TCPDF::class), 'TCPDF is unavailable.');

$png = (new PngWriter())->write(new QrCode('https://example.test/staff_side/'))->getString();
smokeCheck(str_starts_with($png, "\x89PNG\r\n\x1a\n"), 'QR PNG generation failed.');

echo "Smoke checks passed.\n";
