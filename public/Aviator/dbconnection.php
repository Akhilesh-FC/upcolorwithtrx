<?php

$servername = "localhost";
$username = "u853168956_upcolor";
$password = "u853168956_Upcolor";
$dbname = "u853168956_upcolor";

// Create connection  
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

