<?php
session_start();
include '../config/connection.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['content']) && !empty($data['content'])) {
    $content = $data['content'];
    
    $userId = $_SESSION['user_id']; 

    $sql = "INSERT INTO posts (user_id, content) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $userId, $content);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Post content cannot be empty']);
}
?>
