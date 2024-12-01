<?php
include '../../config/general/connection.php';

header('Content-Type: application/json');

// Fetch all schools from the database
$sql = "SELECT id, name FROM schools ORDER BY name ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $schools = [];
    while ($row = $result->fetch_assoc()) {
        $schools[] = $row;
    }
    echo json_encode($schools);
} else {
    echo json_encode([]);
}

$conn->close();
?>
