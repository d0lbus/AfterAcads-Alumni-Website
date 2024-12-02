<?php
// events_controller.php
session_start();
include '../../config/general/connection.php';

// Check if the user is authenticated
if (!isset($_SESSION['email'])) {
    header("Location: loginpage.php");
    exit();
}

header('Content-Type: application/json');

// Pagination and Filters

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$school_id = isset($_GET['school_id']) ? $_GET['school_id'] : null;
$search = isset($_GET['search']) ? $_GET['search'] : null;
$events_per_page = 5;
$offset = ($page - 1) * $events_per_page;

// Base SQL query
$sql = "SELECT events.*, schools.name AS school_name FROM events 
        LEFT JOIN schools ON events.school_id = schools.id WHERE 1=1";

// Apply school filter
if ($school_id) {
    $sql .= " AND events.school_id = ?";
}

// Apply search filter
if ($search) {
    $sql .= " AND (events.title LIKE ? OR events.description LIKE ?)";
}

// Count total events for pagination
$count_sql = str_replace("SELECT events.*, schools.name AS school_name", "SELECT COUNT(*) AS total", $sql);
$count_stmt = $conn->prepare($count_sql);

// Bind parameters for count query
if ($school_id && $search) {
    $search_param = '%' . $search . '%';
    $count_stmt->bind_param("iss", $school_id, $search_param, $search_param);
} elseif ($school_id) {
    $count_stmt->bind_param("i", $school_id);
} elseif ($search) {
    $search_param = '%' . $search . '%';
    $count_stmt->bind_param("ss", $search_param, $search_param);
}

$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_events = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_events / $events_per_page);

// Add pagination to main query
$sql .= " LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);

// Bind parameters for main query
if ($school_id && $search) {
    $stmt->bind_param("issii", $school_id, $search_param, $search_param, $events_per_page, $offset);
} elseif ($school_id) {
    $stmt->bind_param("iii", $school_id, $events_per_page, $offset);
} elseif ($search) {
    $stmt->bind_param("ssii", $search_param, $search_param, $events_per_page, $offset);
} else {
    $stmt->bind_param("ii", $events_per_page, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

echo json_encode([
    'events' => $events,
    'pagination' => [
        'total_pages' => $total_pages,
        'current_page' => $page,
    ],
]);
?>
