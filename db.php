<?php
// Database connection for InfinityFree
$servername = "sql107.infinityfree.com"; // Hostname from InfinityFree Control Panel
$port       = 3306;                       // Default MySQL port for InfinityFree
$username   = "if0_41196114";             // Your InfinityFree MySQL username
$password   = "AM5RlhTPgwx";              // Your MySQL password
$dbname     = "if0_41196114_csp";         // Your InfinityFree database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set UTF-8 encoding
$conn->set_charset("utf8mb4");
?>
