<?php
session_start();  
require_once('../config/connection.php');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['content']) && !empty($data['content'])) {
    $content = $data['content'];

    // Assuming the user is logged in and the user ID is stored in session
    // Replace with the session value for production
    $userId = $_SESSION['user_id'] ?? 1; // Using session user_id or defaulting to user ID 1 for testing

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
