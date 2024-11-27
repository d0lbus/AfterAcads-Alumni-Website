<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../pages/loginpage.php");
    exit();
}

include '../config/connection.php';

$email = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Error: User not found.";
    exit();
}

?>
