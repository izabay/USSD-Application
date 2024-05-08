<?php
// Database connection parameters
$host = 'localhost';
$dbname = 'offeringsystem'; // Updated database name
$username = 'root';
$password = ''; // Update with your database password if applicable


    // Create a new PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
   
?>
