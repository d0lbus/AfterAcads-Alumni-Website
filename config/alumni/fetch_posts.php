<?php
include 'connection.php';

header('Content-Type: application/json');

// Fetch filters from the request
$school_id = isset($_GET['school_id']) ? intval($_GET['school_id']) : null;
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : null;
$batch = isset($_GET['batch']) ? $_GET['batch'] : null;
$sort = isset($_GET['sort']) && $_GET['sort'] === 'oldest' ? 'ASC' : 'DESC';

// Build the base SQL query
$sql = "SELECT posts.*, users.first_name, users.last_name, schools.name AS school, courses.name AS course 
        FROM posts
        JOIN users ON posts.user_id = users.id
        LEFT JOIN schools ON posts.school_id = schools.id
        LEFT JOIN courses ON posts.course_id = courses.id";

// Add filters if provided
$conditions = [];
$params = [];
$types = '';

if ($school_id) {
    $conditions[] = "posts.school_id = ?";
    $params[] = $school_id;
    $types .= 'i';
}

if ($course_id) {
    $conditions[] = "posts.course_id = ?";
    $params[] = $course_id;
    $types .= 'i';
}

if ($batch) {
    $conditions[] = "posts.batch = ?";
    $params[] = $batch;
    $types .= 's';
}

// Apply conditions to the query
if (!empty($conditions)) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}

// Add sorting
$sql .= " ORDER BY posts.created_at $sort";

// Prepare and execute the query
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Fetch results
$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = [
        'id' => $row['id'],
        'full_name' => $row['first_name'] . ' ' . $row['last_name'],
        'content' => $row['content'],
        'tags' => explode(',', $row['tags']), // Assuming tags are stored as comma-separated values
        'school' => $row['school'] ?: 'N/A',
        'course' => $row['course'] ?: 'N/A',
        'batch' => $row['batch'] ?: 'N/A',
        'image' => $row['image'] ? base64_encode($row['image']) : null,
        'created_at' => $row['created_at']
    ];
}

// Return the posts as JSON
echo json_encode($posts);

$stmt->close();
$conn->close();
?>
