<?php
// Update this with your actual database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_ticketbooking";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check if the connection was successful
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    //echo "Connected successfully";
    // If no error, proceed with your query
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
