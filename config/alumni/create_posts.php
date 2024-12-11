// References:
// 1. W3Schools - For PHP basics, MySQL integration, and prepared statements. (https://www.w3schools.com/php/php_mysql_intro.asp)
// 2. StackOverflow - For file uploads, input validation, and error handling. (https://stackoverflow.com/questions/529160/how-to-upload-file-using-php)
// 3. GeeksforGeeks - For session management, error handling, and best practices. (https://www.geeksforgeeks.org/how-to-handle-errors-in-php/)
// 4. YouTube - For video tutorials on PHP, MySQL, and handling file uploads. (Search "PHP MySQL file upload tutorial" on YouTube)
// 5. ChatGPT - For generating and explaining general PHP concepts, error handling, and best practices.
// 6. PHP Manual - For details on the `json_encode`, `json_decode`, and `file_get_contents` functions. (https://www.php.net/manual/en/function.json-encode.php)

<?php
header('Content-Type: application/json');
session_start();
include '../../config/general/connection.php';

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Validate input
$content = $_POST['content'] ?? null;
$schoolId = $_POST['school_id'] ?? null;
$courseId = $_POST['course_id'] ?? null;
$batchId = $_POST['batch_id'] ?? null;
$tags = json_decode($_POST['tags'] ?? "[]", true); // Parse JSON tags input
$imageBlob = null;

// Validate image upload
if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
    $imageBlob = file_get_contents($_FILES['image']['tmp_name']);
}

// Check required fields
if (!$content || !$schoolId || !$courseId || !$batchId) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

// Get user ID from session
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated.']);
    exit;
}

try {
    // Insert post into posts table
    $stmt = $conn->prepare("INSERT INTO posts (user_id, content, school_id, course_id, batch_id, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $userId, $content, $schoolId, $courseId, $batchId, $imageBlob);

    if ($stmt->execute()) {
        $postId = $conn->insert_id;

        // Handle tags
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                $tag = trim($tag);

                // Check if tag already exists in tags table
                $tagCheckStmt = $conn->prepare("SELECT id FROM tags WHERE name = ?");
                $tagCheckStmt->bind_param("s", $tag);
                $tagCheckStmt->execute();
                $tagCheckResult = $tagCheckStmt->get_result();

                if ($tagCheckResult->num_rows > 0) {
                    $tagRow = $tagCheckResult->fetch_assoc();
                    $tagId = $tagRow['id'];
                } else {
                    // Insert new tag if it doesn't exist
                    $tagInsertStmt = $conn->prepare("INSERT INTO tags (name) VALUES (?)");
                    $tagInsertStmt->bind_param("s", $tag);
                    $tagInsertStmt->execute();
                    $tagId = $conn->insert_id;
                    $tagInsertStmt->close();
                }
                $tagCheckStmt->close();

                // Insert post-tag relation into post_tags table
                $postTagStmt = $conn->prepare("INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)");
                $postTagStmt->bind_param("ii", $postId, $tagId);
                $postTagStmt->execute();
                $postTagStmt->close();
            }
        }

        echo json_encode(['success' => true, 'message' => 'Post created successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create post.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
