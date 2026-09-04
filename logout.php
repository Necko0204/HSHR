<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/admin_session.php';
hshr_clear_session('/');
header('Location: index.php');
exit;
