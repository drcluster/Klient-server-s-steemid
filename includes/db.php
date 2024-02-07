<?php
$servername = "localhost";
$username = "eestibus";
$password = "5ZfD2mlMZgSu7YT4";
$database = "eestibus";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);
$conn->query("SET NAMES 'utf8'");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>