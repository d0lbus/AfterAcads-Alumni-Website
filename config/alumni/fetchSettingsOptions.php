<?php
include '../../config/general/connection.php';
include '../../config/alumni/header.php';

// Authenticate User
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['error' => 'User not authenticated.']);
    exit;
}

// Fetch User Data
$user_query = "SELECT * FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();

// Fetch Schools (Exclude ID 1)
$schools_query = "SELECT id, name FROM schools WHERE id != 1";
$schools_result = $conn->query($schools_query);
$schools = $schools_result ? $schools_result->fetch_all(MYSQLI_ASSOC) : [];

// Fetch Courses (Exclude ID 1)
$courses_query = "SELECT id, name FROM courses";
$courses_result = $conn->query($courses_query);
$courses = $courses_result ? $courses_result->fetch_all(MYSQLI_ASSOC) : [];

// Fetch Batches
$batches_query = "SELECT batch_number FROM batches WHERE id != 1 ORDER BY batch_number ASC";
$batches_result = $conn->query($batches_query);
$batches = $batches_result ? $batches_result->fetch_all(MYSQLI_ASSOC) : [];

// Return as JSON
echo json_encode([
    'user' => $user,
    'schools' => $schools,
    'courses' => $courses,
    'batches' => $batches
]);
?>
