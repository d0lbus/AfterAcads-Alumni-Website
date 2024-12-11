// References:
// 1. W3Schools - For PHP basics, MySQL integration, prepared statements, and JSON handling. (https://www.w3schools.com/php/php_mysql_intro.asp)
// 2. StackOverflow - For input validation and error handling. (https://stackoverflow.com/questions/6031904/how-to-validate-and-filter-user-input-in-php)
// 3. GeeksforGeeks - For error handling, session management, and best practices. (https://www.geeksforgeeks.org/how-to-handle-errors-in-php/)
// 4. YouTube - For video tutorials on PHP, MySQL, and JSON handling. (Search "PHP MySQL JSON tutorial" on YouTube)
// 5. ChatGPT - For generating and explaining general PHP concepts, error handling, and best practices.
// 6. PHP Manual - For details on the `json_encode` and `json_decode` functions. (https://www.php.net/manual/en/function.json-encode.php)

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
