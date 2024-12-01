<?php
include '../../config/general/connection.php';

$postId = $_GET['post_id'] ?? null;

if (!$postId) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT comments.*, users.first_name, users.last_name 
        FROM comments 
        JOIN users ON comments.user_id = users.id 
        WHERE comments.post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $postId);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];
while ($row = $result->fetch_assoc()) {
    $comments[] = [
        "user_name" => $row['first_name'] . ' ' . $row['last_name'],
        "comment" => $row['comment'],
        "created_at" => $row['created_at']
    ];
}

echo json_encode($comments);

$stmt->close();
$conn->close();
?>
