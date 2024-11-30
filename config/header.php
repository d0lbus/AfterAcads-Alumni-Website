<?php
session_start();

include 'connection.php'; 

if (!isset($_SESSION['email'])) {
    header("Location: ../../pages/alumni/loginpage.php");
    exit();
}


// Fetch the logged-in user's details
$email = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql); 
if (!$stmt) {
    die("Database error: " . $conn->error); 
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); 
} else {
    echo "Error: User not found.";
    exit();
}


function getAuthenticatedUser() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    include 'connection.php';

    if (!isset($_SESSION['email'])) {
        header("Location: ../pages/alumni/loginpage.php");
        exit();
    }

    $email = $_SESSION['email'];
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Database error: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        die("User not authenticated. Please log in.");
    }
}
?>
