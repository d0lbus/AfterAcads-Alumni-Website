<?php
include '../../config/general/connection.php';

$school_id = isset($_GET['school_id']) ? intval($_GET['school_id']) : null;
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : null;
$batch_id = isset($_GET['batch_id']) ? intval($_GET['batch_id']) : null;
$sort = isset($_GET['sort']) && $_GET['sort'] === 'oldest' ? 'ASC' : 'DESC';

// Base SQL query
$sql = "SELECT posts.*, users.first_name, users.last_name, users.profile_picture, 
        schools.name AS school_name, courses.name AS course_name, batches.batch_number
        FROM posts
        JOIN users ON posts.user_id = users.id
        LEFT JOIN schools ON posts.school_id = schools.id
        LEFT JOIN courses ON posts.course_id = courses.id
        LEFT JOIN batches ON posts.batch_id = batches.id";

// Add filters if provided
$conditions = [];
$params = [];
$param_types = '';

if ($school_id) {
    $conditions[] = "posts.school_id = ?";
    $params[] = $school_id;
    $param_types .= 'i';
}

if ($course_id) {
    $conditions[] = "posts.course_id = ?";
    $params[] = $course_id;
    $param_types .= 'i';
}

if ($batch_id) {
    $conditions[] = "posts.batch_id = ?";
    $params[] = $batch_id;
    $param_types .= 'i';
}

// Append conditions to the SQL query
if (!empty($conditions)) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}

// Add sorting
$sql .= " ORDER BY posts.created_at $sort";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error preparing SQL query: " . $conn->error);
}

if (!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Fetch and process results
$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = [
        'id' => $row['id'],
        'full_name' => $row['first_name'] . ' ' . $row['last_name'],
        'content' => $row['content'],
        'school' => $row['school_name'] ?? 'N/A',
        'course' => $row['course_name'] ?? 'N/A',
        'batch' => $row['batch_number'] ?? 'N/A',
        'tags' => $row['tags'] ?? 'No tags',
        'image' => !empty($row['image']) ? base64_encode($row['image']) : null,
        'created_at' => $row['created_at']
    ];
}

// Return the posts as JSON
echo json_encode($posts);

$stmt->close();
$conn->close();
?>
