<?php
$servername = "sql12.freesqldatabase.com";
$username = "sql12762545";
$password = "KBawSiFK9P";
$dbname = "sql12762545";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
