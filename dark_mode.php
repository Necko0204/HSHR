<?php
session_name('admin_session');
session_start();
include 'db_config.php'; // Include MySQLi database connection

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "error" => "Not logged in"]);
    exit();
}

$admin_id = $_SESSION['admin_id'];

if (isset($_POST['fetch_mode'])) {
    $stmt = $conn->prepare("SELECT darkmodeOn FROM admin WHERE id = ?");
    $stmt->bind_param("s", $admin_id);
    $stmt->execute();
    $stmt->bind_result($dark_mode);
    $stmt->fetch();
    $stmt->close();
    
    echo json_encode(["success" => true, "dark_mode" => $dark_mode]);
    exit();
}

if (isset($_POST['dark_mode'])) {
    $dark_mode = ($_POST['dark_mode'] == 1) ? 1 : 0;
    $_SESSION['dark_mode'] = $dark_mode;

    $stmt = $conn->prepare("UPDATE admin SET darkmodeOn = ? WHERE id = ?");
    $stmt->bind_param("is", $dark_mode, $admin_id);
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }
    $stmt->close();
}
?>
