<?php
include '../../config/general/connection.php';

$school_id = $_GET['school_id'] ?? null;
$course_id = $_GET['course_id'] ?? null;
$batch_id = $_GET['batch_id'] ?? null;
$sort_order = $_GET['sort'] === 'oldest' ? 'ASC' : 'DESC';

// Base SQL Query
$sql = "SELECT 
            posts.*,
            users.first_name, 
            users.last_name, 
            schools.name AS school_name,
            courses.name AS course_name,
            batches.batch_number AS batch_name,
            COALESCE(GROUP_CONCAT(DISTINCT tags.name), '') AS tags
        FROM posts
        JOIN users ON posts.user_id = users.id
        LEFT JOIN schools ON posts.school_id = schools.id
        LEFT JOIN courses ON posts.course_id = courses.id
        LEFT JOIN batches ON posts.batch_id = batches.id
        LEFT JOIN post_tags ON posts.id = post_tags.post_id
        LEFT JOIN tags ON post_tags.tag_id = tags.id";

// Apply filters dynamically
$conditions = [];
$params = [];

// Apply filters only if they are not set to "ALL" or empty
if ($school_id && $school_id !== 'ALL') {
    $conditions[] = "posts.school_id = ?";
    $params[] = $school_id;
}
if ($course_id && $course_id !== 'ALL') {
    $conditions[] = "posts.course_id = ?";
    $params[] = $course_id;
}
if (!empty($batch_id) && $batch_id !== 'ALL') {
    $conditions[] = "posts.batch_id = ?";
    $params[] = $batch_id;
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

// Group by post ID to handle tags correctly
$sql .= " GROUP BY posts.id ORDER BY posts.created_at $sort_order";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = [
        'id' => $row['id'],
        'full_name' => $row['first_name'] . ' ' . $row['last_name'],
        'content' => $row['content'],
        'school' => $row['school_name'] ?: 'ALL',
        'course' => $row['course_name'] ?: 'ALL',
        'batch' => $row['batch_name'] ?: 'ALL',
        'tags' => $row['tags'] ? explode(',', $row['tags']) : [],
        'image' => $row['image'] ? base64_encode($row['image']) : null,
        'created_at' => $row['created_at'],
    ];
}

echo json_encode($posts);

$stmt->close();
$conn->close();
?>
