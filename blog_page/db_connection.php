<?php
// Database connection parameters
$servername = "localhost";  // Usually "localhost"
$username = "root";         // Default username for XAMPP is "root"
$password = "";             // Default password for XAMPP is empty
$dbname = "blog_posts";     // Your database name

// Create the connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
