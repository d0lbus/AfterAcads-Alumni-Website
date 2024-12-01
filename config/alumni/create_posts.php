<?php
session_start();
include '../../config/general/connection.php';

// Fetch the logged-in user's ID
$email = $_SESSION['email'];
$sql = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];

// Check if image is uploaded
$image_blob = null;
if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
    $image_blob = file_get_contents($_FILES['image']['tmp_name']);
}

// Get the post content and tag from the request
$content = $_POST['content'];
$tag = $_POST['tag'];

// Prepare and execute the insert query
$sql = "INSERT INTO posts (user_id, content, tag, image) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isss", $user_id, $content, $tag, $image_blob);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

$stmt->close();
$conn->close();
?>
