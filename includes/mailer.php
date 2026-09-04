<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;

function hshr_configure_mailer(PHPMailer $mailer): void
{
    $host = trim((string) getenv('SMTP_HOST'));
    $username = trim((string) getenv('SMTP_USERNAME'));
    $password = (string) getenv('SMTP_PASSWORD');
    $fromEmail = trim((string) getenv('SMTP_FROM_EMAIL'));
    $fromName = trim((string) (getenv('SMTP_FROM_NAME') ?: 'Holy Spirit Human Resource'));
    $port = filter_var(getenv('SMTP_PORT') ?: '587', FILTER_VALIDATE_INT);
    $encryption = strtolower(trim((string) (getenv('SMTP_ENCRYPTION') ?: 'tls')));

    if ($host === '' || $username === '' || $password === '' || !filter_var($fromEmail, FILTER_VALIDATE_EMAIL) || $port === false) {
        throw new RuntimeException('Email delivery is not configured.');
    }
    if (!in_array($encryption, ['tls', 'ssl'], true)) {
        throw new RuntimeException('SMTP_ENCRYPTION must be tls or ssl.');
    }

    $mailer->isSMTP();
    $mailer->Host = $host;
    $mailer->SMTPAuth = true;
    $mailer->Username = $username;
    $mailer->Password = $password;
    $mailer->SMTPSecure = $encryption === 'ssl'
        ? PHPMailer::ENCRYPTION_SMTPS
        : PHPMailer::ENCRYPTION_STARTTLS;
    $mailer->Port = $port;
    $mailer->CharSet = 'UTF-8';
    $mailer->Timeout = 15;
    $mailer->setFrom($fromEmail, $fromName);
}

function hshr_application_base_url(): string
{
    $configured = rtrim(trim((string) getenv('APP_BASE_URL')), '/');
    if ($configured !== '' && filter_var($configured, FILTER_VALIDATE_URL)) {
        return $configured;
    }

    return 'http://localhost:8080';
}

