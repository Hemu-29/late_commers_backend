<?php
// Database credentials
require "config.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// SQL query to fetch data from the table
$sql = "SELECT * FROM latecomers";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latecomers Data</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 80%; margin: 20px auto; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<h1>Latecomers Data</h1>

<?php
if ($result->num_rows > 0) {
    echo "<table>";
    echo "<thead><tr><th>ID</th><th>Hallticket No</th><th>Scanner ID</th><th>Method</th><th>Scanned At</th></tr></thead>";
    echo "<tbody>";

    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["Hallticket_No"] . "</td>";
        echo "<td>" . $row["scanner_id"] . "</td>";
        echo "<td>" . $row["method"] . "</td>";
        echo "<td>" . $row["scanned_at"] . "</td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<p>No latecomers found in the database.</p>";
}

$conn->close();
?>

</body>
</html>