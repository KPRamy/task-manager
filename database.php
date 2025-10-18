<?php
// Step 1: Database connection details
$servername = "localhost"; // The database server (your local computer)
$username = "root";        // The default username for XAMPP 
$password = "";            // Usually empty for local setup
$dbname = "task_manager_db"; // The database name you created in phpMyAdmin


// Step 2: Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Step 3: Check if the connection worked
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
