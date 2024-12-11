/// References:
// 1. W3Schools - For PHP basics, MySQL integration, and prepared statements. (https://www.w3schools.com/php/php_mysql_intro.asp)
// 2. StackOverflow - For session management, input validation, and MySQL queries. (https://stackoverflow.com/questions/11324991/how-to-prepare-a-select-query-in-php-with-mysqli)
// 3. GeeksforGeeks - For understanding SQL queries, including `LEFT JOIN` and subqueries. (https://www.geeksforgeeks.org/sql-joins-with-examples/)
// 4. YouTube - For video tutorials on PHP, MySQL, and query filtering:
//    - PHP & MySQL Database Tutorial - https://www.youtube.com/watch?v=4z8hgtbrO5k
//    - PHP MySQL Prepared Statements Tutorial - https://www.youtube.com/watch?v=dh52_1Vvwgg
//    - PHP MySQL Query Filter Tutorial - https://www.youtube.com/watch?v=TKl-WnpV9WA
// 5. ChatGPT - For generating and explaining general PHP concepts, prepared statements, and query filtering.
// 6. PHP Manual - For details on `session_start`, `mysqli_prepare`, and other PHP functions. (https://www.php.net/manual/en/function.session-start.php)

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
$school_id = isset($_GET['school_id']) ? intval($_GET['school_id']) : null;
$filter = isset($_GET['filter']) ? $_GET['filter'] : null; // Participation filter

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

// Prepare and execute
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
]);
?>
