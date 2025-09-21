<?php
// Database credentials
$host = "sql303.infinityfree.com";
$user = "if0_39985753";
$pass = "Ganeshkumari3";
$db = "if0_39985753_late_comers";

// Attempt to establish a connection with exception handling
try {
    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    // In a production environment, you would log the error instead of displaying it.
    // For debugging, displaying the error can be helpful.
    die("Database connection error: " . $e->getMessage());
}
?>