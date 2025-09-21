<?php
// Read MySQL credentials from environment variables (set in Railway)
$host = getenv("MYSQLHOST") ?: "localhost";
$user = getenv("MYSQLUSER") ?: "root";
$pass = getenv("MYSQLPASSWORD") ?: "";
$db   = getenv("MYSQLDATABASE") ?: "late_comers";
$port = getenv("MYSQLPORT") ?: 3306;

// Attempt to establish a connection with exception handling
try {
    $conn = new mysqli($host, $user, $pass, $db, $port);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    // In production: log error instead of displaying it
    header("Content-Type: application/json");
    echo json_encode([
        "status" => "error",
        "message" => "Database connection error",
        "details" => $e->getMessage()
    ]);
    exit;
}
?>
