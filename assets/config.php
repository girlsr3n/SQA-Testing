<?php
$servername = "localhost";
$username = "root";  // user MySQL 
$password = "";      // password MySQL 
$dbname = "customer"; // nama database

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}
?>
