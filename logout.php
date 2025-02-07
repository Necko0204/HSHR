<?php
session_name('admin_session'); // Ensure correct session name
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Remove sessionStorage (in JavaScript)
echo "<script>sessionStorage.clear();</script>";

// Redirect to login page
header("Location: index.php");
exit();
?>
