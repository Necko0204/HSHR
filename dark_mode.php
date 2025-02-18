<?php
include 'db_config.php';

// Debug: Check if session is properly set
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['dark_mode'])) {
    $_SESSION['dark_mode'] = $_POST['dark_mode'] === 'true' ? true : false;
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}
?>
