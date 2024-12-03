<?php
session_start();
include '../../config/general/connection.php';

// Validate user session
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../pages/alumni/settings.php?error=unauthorized");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get inputs
$first_name = $_POST['first-name'];
$middle_name = $_POST['middle-name'];
$last_name = $_POST['last-name'];
$bio = $_POST['add-bio'];
$address = $_POST['change-address'];
$school_id = $_POST['school'];
$course_id = $_POST['course'];
$batch_id = $_POST['batch'];

// Handle profile picture upload
if (!empty($_FILES['profile-picture']['tmp_name'])) {
    $image = $_FILES['profile-picture'];
    if ($image['size'] > 2 * 1024 * 1024) {
        header("Location: ../../pages/alumni/settings.php?error=Image too large");
        exit;
    }
    $allowed_types = ['image/jpeg', 'image/png'];
    if (!in_array($image['type'], $allowed_types)) {
        header("Location: ../../pages/alumni/settings.php?error=Invalid image format");
        exit;
    }
    $image_data = file_get_contents($image['tmp_name']);
} else {
    $image_data = null;
}

// Update user data
$sql = "UPDATE users SET first_name = ?, middle_name = ?, last_name = ?, bio = ?, address = ?, school_id = ?, course_id = ?, batch_id = ?, profile_picture = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    'ssssiiiibi',
    $first_name,
    $middle_name,
    $last_name,
    $bio,
    $address,
    $school_id,
    $course_id,
    $batch_id,
    $image_data,
    $user_id
);
if ($stmt->execute()) {
    header("Location: ../../pages/alumni/settings.php?success=true");
    exit;
} else {
    header("Location: ../../pages/alumni/settings.php?error=Database error");
    exit;
}
?>
