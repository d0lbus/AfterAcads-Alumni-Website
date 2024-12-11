// References:
// 1. PHP Manual - Handling File Uploads (https://www.php.net/manual/en/features.file-upload.php)
// 2. ChatGPT - For safely managing file uploads, reading image content, and inserting binary data into SQL databases.

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

// Check if the image is uploaded
if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
    $image_blob = file_get_contents($_FILES['image']['tmp_name']); // Read image contents
    $sql = "INSERT INTO posts (user_id, image) VALUES (?, ?)"; // Insert image into `posts`
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $image_blob);

    if ($stmt->execute()) {
        echo "Image uploaded successfully!";
    } else {
        echo "Error: Unable to upload image.";
    }

    $stmt->close();
} else {
    echo "No image file provided.";
}

$conn->close();
?>