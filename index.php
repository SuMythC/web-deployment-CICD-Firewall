<?php
$servername = "host_name"; // Your database server
$username = "username"; // Your database username
$password = "password"; // Your database password
$dbname = "databasename"; // Your database name

try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to Database - $dbname, Host - $servername successfully";
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Close connection
$conn = null;
?>