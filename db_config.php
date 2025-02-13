<?php
$servername = "sql301.infinityfree.com";
$username = "if0_38306620";
$password = "8eu4xCWPEtqGRY";
$dbname = "if0_38306620_humanresource";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
