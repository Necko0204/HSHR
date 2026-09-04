<?php
declare(strict_types=1);

require_once __DIR__ . '/admin_session.php';

if (empty($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

