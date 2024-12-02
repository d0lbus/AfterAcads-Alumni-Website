<?php
session_start();
include '../../config/general/connection.php';

if (!isset($_SESSION['email'])) {
    header("Location: loginpage.php");
    exit();
}

header('Content-Type: application/json');

$user_id = $_SESSION['user_id']; 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$school_id = isset($_GET['school_id']) ? $_GET['school_id'] : null;
$search = isset($_GET['search']) ? $_GET['search'] : null;
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$events_per_page = 5;
$offset = ($page - 1) * $events_per_page;

// Base SQL query
$sql = "
    SELECT 
        events.*, 
        schools.name AS school_name,
        (SELECT COUNT(*) FROM event_participants WHERE event_participants.event_id = events.id AND status = 'going') AS going_count,
        (SELECT COUNT(*) FROM event_participants WHERE event_participants.event_id = events.id AND status = 'interested') AS interested_count
    FROM events
    LEFT JOIN schools ON events.school_id = schools.id
    WHERE 1=1
";

// Apply filter for "going" or "interested"
if ($filter === 'going') {
    $sql .= " AND EXISTS (
        SELECT 1 
        FROM event_participants 
        WHERE event_participants.event_id = events.id 
        AND event_participants.user_id = ? 
        AND event_participants.status = 'going'
    )";
} elseif ($filter === 'interested') {
    $sql .= " AND EXISTS (
        SELECT 1 
        FROM event_participants 
        WHERE event_participants.event_id = events.id 
        AND event_participants.user_id = ? 
        AND event_participants.status = 'interested'
    )";
}

// Apply school filter
if ($school_id) {
    $sql .= " AND events.school_id = ?";
}

// Apply search filter
if ($search) {
    $sql .= " AND (events.title LIKE ? OR events.description LIKE ?)";
}

// Remove LIMIT and OFFSET for count query
$count_sql = preg_replace("/SELECT .*? FROM/", "SELECT COUNT(*) AS total FROM", $sql);
$count_sql = preg_replace("/LIMIT \d+ OFFSET \d+/", "", $count_sql); // Ensure pagination is removed

$count_stmt = $conn->prepare($count_sql);

$params = [];
$types = "";

if ($filter !== 'all') {
    $params[] = $user_id;
    $types .= "i";
}

if ($school_id) {
    $params[] = $school_id;
    $types .= "i";
}

if ($search) {
    $search_param = '%' . $search . '%';
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "ss";
}

// Bind parameters for count query
if (!empty($params)) {
    $count_stmt->bind_param($types, ...$params);
}

$count_stmt->execute();
$count_result = $count_stmt->get_result();
$count_row = $count_result->fetch_assoc();

// Check if the total key exists
$total_events = isset($count_row['total']) ? $count_row['total'] : 0;
$total_pages = ceil($total_events / $events_per_page);

// Add pagination to main query
$sql .= " LIMIT ? OFFSET ?";
$params[] = $events_per_page;
$params[] = $offset;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
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
