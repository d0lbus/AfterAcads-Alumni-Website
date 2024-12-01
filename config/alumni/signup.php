<?php
include '../config/general/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = upperCaseFirstLetter($_POST['firstName']);
    $middleName = upperCaseFirstLetter($_POST['middleName']);
    $lastName = upperCaseFirstLetter($_POST['lastName']);
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $agreedToTerms = isset($_POST['agree-terms']) ? 1 : 0;

    // Ensure passwords match (server-side check)
    if ($password !== $confirmPassword) {
        echo "Passwords do not match.";
        exit();
    }

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
    $sql = "INSERT INTO users (first_name, middle_name, last_name, email, password_hash, agreed_to_terms) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssi', $firstName, $middleName, $lastName, $email, $passwordHash, $agreedToTerms);

    if ($stmt->execute()) {
        header("Location: ../../pages/alumni/loginpage.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}


// Uppercase First Letter of firstName & lastName
function upperCaseFirstLetter($data){
    $data = ucwords($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
