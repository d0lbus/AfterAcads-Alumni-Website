<?php
include '../config/connection.php'; 

$tag = isset($_GET['tag']) ? $_GET['tag'] : null;
$search = isset($_GET['search']) ? $_GET['search'] : null;

// Base SQL query to join posts and users tables
$sql = "SELECT posts.*, users.first_name, users.last_name 
        FROM posts 
        JOIN users ON posts.user_id = users.id";

// If a tag is provided, filter by tag
if ($tag) {
    $sql .= " WHERE posts.tag = ?";
}

// If a search query is provided, search in post content, first name, last name, or full name
if ($search) {
    $sql .= $tag ? " AND" : " WHERE";
    $sql .= " (posts.content LIKE ? OR users.first_name LIKE ? OR users.last_name LIKE ? OR CONCAT(users.first_name, ' ', users.last_name) LIKE ?)";
}

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind parameters based on whether a tag or search query is provided
if ($tag && $search) {
    $search_term = '%' . $search . '%';
    // Bind both tag and search term
    $stmt->bind_param("sssss", $tag, $search_term, $search_term, $search_term, $search_term);
} elseif ($tag) {
    // Bind only the tag
    $stmt->bind_param("s", $tag);
} elseif ($search) {
    // Bind only the search term
    $search_term = '%' . $search . '%';
    $stmt->bind_param("ssss", $search_term, $search_term, $search_term, $search_term);
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

// Return posts as JSON
echo json_encode($posts);

$stmt->close();
$conn->close();
?>
