// References:
// 1. W3Schools - For PHP basics, MySQL integration, and prepared statements. (https://www.w3schools.com/php/php_mysql_intro.asp)
// 2. StackOverflow - For session management, input validation, and MySQL queries. (https://stackoverflow.com/questions/11324991/how-to-prepare-a-select-query-in-php-with-mysqli)
// 3. GeeksforGeeks - For understanding SQL queries, including `LEFT JOIN` and subqueries. (https://www.geeksforgeeks.org/sql-joins-with-examples/)
// 4. YouTube - For video tutorials on PHP, MySQL, and query filtering:
//    - PHP & MySQL Database Tutorial - https://www.youtube.com/watch?v=4z8hgtbrO5k
//    - PHP MySQL Prepared Statements Tutorial - https://www.youtube.com/watch?v=dh52_1Vvwgg
//    - PHP MySQL Query Filter Tutorial - https://www.youtube.com/watch?v=TKl-WnpV9WA
// 5. ChatGPT - For generating and explaining general PHP concepts, prepared statements, and query filtering.
// 6. PHP Manual - For details on `session_start`, `mysqli_prepare`, and other PHP functions. (https://www.php.net/manual/en/function.session-start.php)

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
