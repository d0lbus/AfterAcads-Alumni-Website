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

// Create a separate count query for pagination
$count_sql = "
    SELECT COUNT(*) AS total
    FROM events
    LEFT JOIN schools ON events.school_id = schools.id
    WHERE 1=1
";

// Add conditions to the count query
if ($filter === 'going') {
    $count_sql .= " AND EXISTS (
        SELECT 1 
        FROM event_participants 
        WHERE event_participants.event_id = events.id 
        AND event_participants.user_id = ? 
        AND event_participants.status = 'going'
    )";
} elseif ($filter === 'interested') {
    $count_sql .= " AND EXISTS (
        SELECT 1 
        FROM event_participants 
        WHERE event_participants.event_id = events.id 
        AND event_participants.user_id = ? 
        AND event_participants.status = 'interested'
    )";
}

if ($school_id) {
    $count_sql .= " AND events.school_id = ?";
}

if ($search) {
    $count_sql .= " AND (events.title LIKE ? OR events.description LIKE ?)";
}

// Prepare parameters for count query
$count_params = [];
$count_types = "";

// Bind user_id for "going" or "interested"
if ($filter !== 'all') {
    $count_params[] = $user_id;
    $count_types .= "i";
}

// Bind school_id
if ($school_id) {
    $count_params[] = $school_id;
    $count_types .= "i";
}

// Bind search terms
if ($search) {
    $search_param = '%' . $search . '%';
    $count_params[] = $search_param;
    $count_params[] = $search_param;
    $count_types .= "ss";
}

$count_stmt = $conn->prepare($count_sql);
if (!empty($count_params)) {
    $count_stmt->bind_param($count_types, ...$count_params);
}
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$count_row = $count_result->fetch_assoc();
$total_events = isset($count_row['total']) ? $count_row['total'] : 0;
$total_pages = ceil($total_events / $events_per_page);

// Add pagination to the main query
$sql .= " LIMIT ? OFFSET ?";
$params = [];
$types = $count_types;

// Append the same filters for the main query
if ($filter !== 'all') {
    $params[] = $user_id;
}
if ($school_id) {
    $params[] = $school_id;
}
if ($search) {
    $params[] = $search_param;
    $params[] = $search_param;
}

// Bind pagination params
$params[] = $events_per_page;
$params[] = $offset;
$types .= "ii";

// Prepare and execute the main query
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

// Return the response
echo json_encode([
    'events' => $events,
    'pagination' => [
        'total_pages' => $total_pages,
        'current_page' => $page,
    ],
]);
?>
