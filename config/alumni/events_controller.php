<?php
// events_controller.php
session_start();
include '../../config/general/connection.php';

// Check if the user is authenticated
if (!isset($_SESSION['email'])) {
    header("Location: loginpage.php");
    exit();
}

// Pagination and Filters
$events_per_page = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$search = isset($_GET['search']) ? $_GET['search'] : null;
$school_id = isset($_GET['school_id']) ? intval($_GET['school_id']) : null;
$offset = ($page - 1) * $events_per_page;

// Build the base query
$sql = "SELECT events.id, events.title, events.description, events.date, events.time, events.location, events.image_path, events.alt_text, schools.name AS school_name
        FROM events
        LEFT JOIN schools ON events.school_id = schools.id
        WHERE 1=1";

// Apply filters
$params = [];
if ($search) {
    $sql .= " AND (events.title LIKE ? OR events.description LIKE ?)";
    $params[] = '%' . $search . '%';
    $params[] = '%' . $search . '%';
}

if ($school_id) {
    $sql .= " AND events.school_id = ?";
    $params[] = $school_id;
}

// Add pagination
$sql .= " ORDER BY events.date ASC LIMIT ? OFFSET ?";
$params[] = $events_per_page;
$params[] = $offset;

// Prepare and execute the query
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$result = $stmt->get_result();

// Fetch all events
$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

// Count total number of events for pagination
$sql_count = "SELECT COUNT(*) AS total FROM events WHERE 1=1";
if ($school_id) {
    $sql_count .= " AND school_id = ?";
}
$stmt_count = $conn->prepare($sql_count);
if ($school_id) {
    $stmt_count->bind_param("i", $school_id);
}
$stmt_count->execute();
$total_events = $stmt_count->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_events / $events_per_page);

// Return JSON response if requested (for AJAX)
if (isset($_GET['ajax']) && $_GET['ajax'] == 'true') {
    header('Content-Type: application/json');
    echo json_encode([
        'events' => $events,
        'pagination' => [
            'total_pages' => $total_pages,
            'current_page' => $page
        ]
    ]);
    exit();
}

$conn->close();
?>
