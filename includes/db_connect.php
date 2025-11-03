
<?php
$db_host = 'localhost';
$db_user = 'root'; // Default XAMPP/WAMP username
$db_pass = ''; // Default XAMPP/WAMP password
$db_name = 'rentbase';

$conn = new mysqli($db_host, $db_user, $db_pass);

// Check connection before trying to select a DB
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$conn->query("CREATE DATABASE IF NOT EXISTS $db_name");

// Select the database
$conn->select_db($db_name);

?>
