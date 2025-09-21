<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

require "config.php"; // DB connection

$method = $_SERVER['REQUEST_METHOD'];

// --- POST: Insert hall ticket into latecomers ---
if ($method === 'POST') {
    // Read input (JSON or form)
    $raw = file_get_contents("php://input");
    $input = json_decode($raw, true);

    if (!$input) {
        $input = $_POST; // fallback to form-data
    }

    $hall = isset($input['Hallticket_No']) ? $conn->real_escape_string($input['Hallticket_No']) : null;
    $scanner = isset($input['scanner_id']) ? $conn->real_escape_string($input['scanner_id']) : null;
    $scanMethod = isset($input['method']) ? $conn->real_escape_string($input['method']) : null;

    if ($hall && $scanner && $scanMethod) {
        $sql = "INSERT INTO latecomers (Hallticket_No, scanner_id, method, scanned_at)
                VALUES ('$hall', '$scanner', '$scanMethod', NOW())";
        if ($conn->query($sql)) {
            echo json_encode([
                "status" => "success",
                "message" => "Inserted: $hall"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => $conn->error
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Missing parameters",
            "received" => $input,
            "raw" => $raw
        ]);
    }
    exit;
}

// --- GET: Autocomplete search for students ---
if ($method === 'GET' && isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $sql = "SELECT Hallticket_No, Student_Name, Branch
            FROM students
            WHERE Hallticket_No LIKE '%$search%'
               OR Student_Name LIKE '%$search%'
            ORDER BY Hallticket_No
            LIMIT 10";

    $result = $conn->query($sql);
    $data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    echo json_encode($data);
    exit;
}

// --- GET: Fetch today's latecomers ---
if ($method === 'GET') {
    $date = isset($_GET['date']) ? $conn->real_escape_string($_GET['date']) : date('Y-m-d');

    $sql = "SELECT l.Hallticket_No, s.Student_Name, s.Branch, l.scanned_at
            FROM latecomers l
            LEFT JOIN students s ON l.Hallticket_No = s.Hallticket_No
            WHERE DATE(l.scanned_at) = '$date'
            ORDER BY l.scanned_at DESC";

    $result = $conn->query($sql);
    $data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    echo json_encode($data);
    exit;
}

// --- Fallback if method not handled ---
echo json_encode([
    "status" => "error",
    "message" => "Unsupported request method"
]);
exit;

?>
