<?php
header('Content-Type: application/json');
session_start();
include '../../config/general/connection.php';

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Parse JSON input
$data = json_decode(file_get_contents('php://input'), true);

$postId = $data['post_id'] ?? null;
$comment = $data['comment'] ?? null;

// Fetch user ID from session
$userId = $_SESSION['user_id'] ?? null;

if (!$postId || !$comment || !$userId) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

try {
    $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $postId, $userId, $comment);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Comment added successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add comment.']);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>
