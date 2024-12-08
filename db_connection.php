<?php
$servername = "localhost";
$username = "root";  // use your database username
$password = "";  // use your database password
$dbname = "alko_db";  // use your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>