<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/staff_session.php';
hshr_clear_session('/staff_side/');
header('Location: index.php');
exit;
