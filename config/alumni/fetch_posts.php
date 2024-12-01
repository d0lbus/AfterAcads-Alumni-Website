<?php
include '../../config/general/connection.php';

$school_id = $_GET['school_id'] ?? null;
$course_id = $_GET['course_id'] ?? null;
$batch_id = $_GET['batch_id'] ?? null;
$sort_order = $_GET['sort'] === 'oldest' ? 'ASC' : 'DESC';

$sql = "SELECT 
            posts.*,
            users.first_name, 
            users.last_name, 
            GROUP_CONCAT(tags.name) AS tags
        FROM posts
        JOIN users ON posts.user_id = users.id
        LEFT JOIN post_tags ON posts.id = post_tags.post_id
        LEFT JOIN tags ON post_tags.tag_id = tags.id";

// Apply filters dynamically
$conditions = [];
$params = [];

if ($school_id) {
    $conditions[] = "posts.school_id = ?";
    $params[] = $school_id;
}
if ($course_id) {
    $conditions[] = "posts.course_id = ?";
    $params[] = $course_id;
}
if ($batch_id) {
    $conditions[] = "posts.batch_id = ?";
    $params[] = $batch_id;
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " GROUP BY posts.id ORDER BY posts.created_at $sort_order";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$result = $stmt->get_result();

$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = [
        'full_name' => $row['first_name'] . ' ' . $row['last_name'],
        'content' => $row['content'],
        'school' => $row['school_id'],
        'course' => $row['course_id'],
        'batch' => $row['batch_id'],
        'tags' => explode(',', $row['tags']),
        'image' => base64_encode($row['image']),
        'created_at' => $row['created_at']
    ];
}

echo json_encode($posts);

$stmt->close();
$conn->close();
?>
