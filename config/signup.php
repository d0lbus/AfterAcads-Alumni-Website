<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $agreedToTerms = isset($_POST['agree-terms']) ? 1 : 0;

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit();
    }

    // Hash password
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Check if the email already exists
    $checkEmail = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();
    
    if ($result->num_rows > 0) {
        echo "Email is already registered.";
        exit();
    }

    // Insert user data into the database
    $sql = "INSERT INTO users (first_name, last_name, email, password_hash, agreed_to_terms) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssi', $firstName, $lastName, $email, $passwordHash, $agreedToTerms);

    if ($stmt->execute()) {
        header("Location: ../pages/loginpage.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
