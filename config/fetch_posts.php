<?php
include '../config/connection.php'; 

$tag = isset($_GET['tag']) ? $_GET['tag'] : null;
$search = isset($_GET['search']) ? $_GET['search'] : null;

$sql = "SELECT posts.*, users.first_name, users.last_name FROM posts 
        JOIN users ON posts.user_id = users.id";

// If a tag is provided, filter by tag
if ($tag) {
    $sql .= " WHERE posts.tag = ?";
}

// If a search query is provided, filter by search query (searching post content)
if ($search) {
    $sql .= $tag ? " AND" : " WHERE";
    $sql .= " posts.content LIKE ?";
}

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind parameters based on whether a tag or search query is provided
if ($tag && $search) {
    $search_term = '%' . $search . '%';
    $stmt->bind_param("ss", $tag, $search_term);
} elseif ($tag) {
    $stmt->bind_param("s", $tag);
} elseif ($search) {
    $search_term = '%' . $search . '%';
    $stmt->bind_param("s", $search_term);
}

$stmt->execute();
$result = $stmt->get_result();

$posts = [];
while ($row = $result->fetch_assoc()) {
    $image_data = base64_encode($row['image']); // Convert BLOB to base64 if there's an image
    $posts[] = [
        'full_name' => $row['first_name'] . ' ' . $row['last_name'],
        'content' => $row['content'],
        'tag' => $row['tag'],
        'image' => $image_data,
        'created_at' => $row['created_at']
    ];
}


echo json_encode($posts);

$stmt->close();
$conn->close();
?>
