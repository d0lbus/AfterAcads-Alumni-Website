<?php
require_once('../config/connection.php');

$sql = "SELECT CONCAT(users.first_name, ' ', users.last_name) AS full_name, posts.content, posts.created_at 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.created_at DESC";
$result = $conn->query($sql);

$posts = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}

echo json_encode($posts);
?>
