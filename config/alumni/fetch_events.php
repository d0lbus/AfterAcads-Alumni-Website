<?php
session_start();

include '../ ../config/general/connection.php'; // Database connection

// Get the search and tag filter inputs
$search = isset($_GET['search']) ? $_GET['search'] : null;
$tag = isset($_GET['tag']) ? $_GET['tag'] : null;

// Base SQL query
$sql = "SELECT * FROM events WHERE 1=1";

// Append tag filter if selected
if ($tag) {
    $sql .= " AND tag = ?";
}

// Append search filter if provided
if ($search) {
    $sql .= " AND (title LIKE ? OR description LIKE ?)";
}

// Prepare the SQL statement
$stmt = $conn->prepare($sql);

// Bind the parameters based on filtering and search criteria
if ($tag && $search) {
    $search_term = '%' . $search . '%';
    $stmt->bind_param('sss', $tag, $search_term, $search_term);
} elseif ($tag) {
    $stmt->bind_param('s', $tag);
} elseif ($search) {
    $search_term = '%' . $search . '%';
    $stmt->bind_param('ss', $search_term, $search_term);
}

$stmt->execute();
$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = [
        'title' => $row['title'],
        'description' => $row['description'],
        'date' => $row['date'],
        'location' => $row['location'],
        'time' => $row['time'],
        'image_path' => $row['image_path'],
        'tag' => $row['tag'],
    ];
}

// Return events as JSON
echo json_encode($events);

$stmt->close();
$conn->close();
?>
