<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/staff_api.php';

$dark = filter_var($_POST['dark_mode'] ?? false, FILTER_VALIDATE_BOOL);
$_SESSION['dark_mode'] = $dark;
staff_api_respond(200, true, 'Appearance updated.', ['dark_mode' => $dark]);
