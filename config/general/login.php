// References:
// - GeeksforGeeks: "PHP mysqli prepared statements" - https://www.geeksforgeeks.org/php-mysqli-prepared-statements/
// - StackOverflow: "How to use password_verify() in PHP" - https://stackoverflow.com/questions/45369253/how-does-password-verify-work-in-php
// - YouTube: "PHP session management tutorial" - https://www.youtube.com/watch?v=G5s3ZcOf3XY
// - ChatGPT: "Explanation of PHP session and password verification" - ChatGPT (2024)

<?php
include '../general/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo "Email and password are required.";
        exit();
    }

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Database error: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $row['password_hash'])) {
            // Set session variables
            session_start();
            $_SESSION['email'] = $row['email'];
            $_SESSION['user_id'] = $row['id']; 

            // Redirect to profile page
            header("Location: ../../pages/alumni/viewProfile.php");
            exit();
        } else {
            // Handle incorrect password
            echo "Incorrect password.";
        }
    } else {
        // Handle user not found
        echo "No user found with that email.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
