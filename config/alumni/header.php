<?php
session_start();

include '../../config/general/connection.php'; 

// Redirect if the user is not logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../../pages/alumni/loginpage.php");
    exit();
}

// Fetch the logged-in user's details, including related information if necessary
$email = $_SESSION['email'];
$sql = "SELECT u.*, b.batch_number AS batch_number, s.name AS school_name, c.name AS course_name
        FROM users u
        LEFT JOIN batches b ON u.batch_id = b.id
        LEFT JOIN schools s ON u.school_id = s.id
        LEFT JOIN courses c ON u.course_id = c.id
        WHERE u.email = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Database error: " . $conn->error);
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Fetch the full user information, including foreign key names
} else {
    echo "Error: User not found.";
    exit();
}

// Function to get authenticated user details for other pages
function getAuthenticatedUser() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    include '../../config/general/connection.php';

    if (!isset($_SESSION['email'])) {
        header("Location: ../pages/alumni/loginpage.php");
        exit();
    }

    $email = $_SESSION['email'];
    $sql = "SELECT u.*, b.batch_number AS batch_number, s.name AS school_name, c.name AS course_name
            FROM users u
            LEFT JOIN batches b ON u.batch_id = b.id
            LEFT JOIN schools s ON u.school_id = s.id
            LEFT JOIN courses c ON u.course_id = c.id
            WHERE u.email = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Database error: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc(); // Return all user information
    } else {
        die("User not authenticated. Please log in.");
    }
}
?>
