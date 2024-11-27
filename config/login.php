<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL statement to prevent SQL injection
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $row['password_hash'])) {
            session_start();
            $_SESSION['email'] = $row['email'];
            $_SESSION['user_id'] = $row['id']; // Store the user's ID in the session
            header("Location: ../pages/alumni/viewProfile.php");
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No user found with that email.";
    }
}
?>
