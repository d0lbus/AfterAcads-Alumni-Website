<?php
session_start();
include '../../config/general/connection.php';

// Validate user session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

header('Content-Type: application/json');

// Inputs
$user_id = $_SESSION['user_id'];
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$school_id = isset($_GET['school_id']) ? intval($_GET['school_id']) : null;
$search = isset($_GET['search']) ? $_GET['search'] : null;
$filter = isset($_GET['filter']) ? $_GET['filter'] : null; // Participation filter
$events_per_page = 5;
$offset = ($page - 1) * $events_per_page;

// Base SQL Query
$sql = "
    SELECT 
        events.*, 
        schools.name AS school_name,
        (SELECT COUNT(*) FROM event_participants WHERE event_participants.event_id = events.id AND status = 'going') AS going_count,
        (SELECT COUNT(*) FROM event_participants WHERE event_participants.event_id = events.id AND status = 'interested') AS interested_count,
        (SELECT status FROM event_participants WHERE event_participants.event_id = events.id AND event_participants.user_id = ?) AS user_status
    FROM events
    LEFT JOIN schools ON events.school_id = schools.id
    WHERE 1=1
";

$params = [$user_id];
$types = "i";

// Apply participation filters ("Going" or "Interested")
if ($filter === 'going') {
    $sql .= " AND EXISTS (
        SELECT 1 
        FROM event_participants 
        WHERE event_participants.event_id = events.id 
        AND event_participants.user_id = ? 
        AND event_participants.status = 'going'
    )";
    $params[] = $user_id;
    $types .= "i";
} elseif ($filter === 'interested') {
    $sql .= " AND EXISTS (
        SELECT 1 
        FROM event_participants 
        WHERE event_participants.event_id = events.id 
        AND event_participants.user_id = ? 
        AND event_participants.status = 'interested'
    )";
    $params[] = $user_id;
    $types .= "i";
}

// Apply school filter
if ($school_id) {
    $sql .= " AND events.school_id = ?";
    $params[] = $school_id;
    $types .= "i";
}

// Apply search filter
if ($search) {
    $sql .= " AND (events.title LIKE ? OR events.description LIKE ?)";
    $search_param = '%' . $search . '%';
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "ss";
}

// Count total events for pagination
$count_sql = preg_replace("/SELECT .*? FROM/", "SELECT COUNT(*) AS total FROM", $sql);
$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param($types, ...$params);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_events = $count_result->fetch_assoc()['total'] ?? 0;
$total_pages = ceil($total_events / $events_per_page);

// Add pagination to main query
$sql .= " LIMIT ? OFFSET ?";
$params[] = $events_per_page;
$params[] = $offset;
$types .= "ii";

// Fetch the events
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
