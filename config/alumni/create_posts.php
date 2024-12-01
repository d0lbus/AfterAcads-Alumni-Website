<?php
session_start();
include '../../config/general/connection.php';

$user_id = $_SESSION['user_id'];
$content = $_POST['content'];
$school_id = $_POST['school_id'];
$course_id = $_POST['course_id'];
$batch_id = $_POST['batch'];
$tags = isset($_POST['tags']) ? explode(',', $_POST['tags']) : [];
$image_blob = null;

if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
    $image_blob = file_get_contents($_FILES['image']['tmp_name']);
}

$conn->begin_transaction();

try {
    // Insert the post
    $sql = "INSERT INTO posts (user_id, content, school_id, course_id, batch_id, image) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $user_id, $content, $school_id, $course_id, $batch_id, $image_blob);
    $stmt->execute();
    $post_id = $stmt->insert_id;

    // Insert tags and link to post
    foreach ($tags as $tag) {
        $tag = trim($tag);

        // Check if tag already exists
        $stmt = $conn->prepare("SELECT id FROM tags WHERE name = ?");
        $stmt->bind_param("s", $tag);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $tag_id = $result->fetch_assoc()['id'];
        } else {
            // Insert new tag
            $stmt = $conn->prepare("INSERT INTO tags (name) VALUES (?)");
            $stmt->bind_param("s", $tag);
            $stmt->execute();
            $tag_id = $stmt->insert_id;
        }

        // Link tag to post
        $stmt = $conn->prepare("INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $post_id, $tag_id);
        $stmt->execute();
    }

    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$stmt->close();
$conn->close();
?>
