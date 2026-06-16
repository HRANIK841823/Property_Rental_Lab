<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "property_lab";
$port = 3307; // Change to 3306 if needed

$conn = new mysqli($host, $user, $pass, $dbname, $port);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>