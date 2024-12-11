// References:
// 1. PHP Manual - For sanitizing and validating form data. (https://www.php.net/manual/en/filter.filters.sanitize.php)
// 2. W3Schools - For using `password_hash()` to securely hash passwords. (https://www.w3schools.com/php/func_password_hash.asp)
// 3. PHP Manual - For handling file uploads and validating file types and size. (https://www.php.net/manual/en/features.file-upload.php)
// 4. PHP Manual - For using `mysqli_prepare()` and `mysqli_bind_param()` for secure database queries. (https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php)
// 5. StackOverflow - For handling user registration and validating inputs (including photo validation). (https://stackoverflow.com/questions/13027772/how-do-i-validate-an-image-upload-in-php)
// 6. ChatGPT - For handling user input sanitization, validation, and photo upload logic, ensuring data integrity and security.

<?php
include '../../config/general/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $firstName = sanitizeInput($_POST['firstName']);
    $middleName = sanitizeInput($_POST['middleName']);
    $lastName = sanitizeInput($_POST['lastName']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $gender = $_POST['gender'] ?? null;
    $agreedToTerms = isset($_POST['agree-terms']) ? 1 : 0;

    // Validate required fields
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword) || empty($gender)) {
        echo "All fields are required.";
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    // Validate gender
    $validGenders = ['Male', 'Female', 'Prefer Not To Say'];
    if (!in_array($gender, $validGenders)) {
        echo "Invalid gender selection.";
        exit();
    }

    // Validate password match
    if ($password !== $confirmPassword) {
        echo "Passwords do not match.";
        exit();
    }

    // Validate file upload
    $alumniPhoto = null;
    if (!empty($_FILES['alumni-photo']['tmp_name'])) {
        $file = $_FILES['alumni-photo'];
        $allowedTypes = ['image/jpeg', 'image/png'];

        if (in_array($file['type'], $allowedTypes)) {
            if ($file['size'] <= 2 * 1024 * 1024) { // 2MB limit
                $alumniPhoto = file_get_contents($file['tmp_name']);
            } else {
                echo "Uploaded file exceeds the 2MB limit.";
                exit();
            }
        } else {
            echo "Invalid file type. Only PNG and JPEG are allowed.";
            exit();
        }
    } else {
        echo "Alumni validation photo is required.";
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
    $sql = "INSERT INTO users (first_name, middle_name, last_name, email, password_hash, agreed_to_terms, gender, alumni_photo_validation) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssbss', $firstName, $middleName, $lastName, $email, $passwordHash, $agreedToTerms, $gender, $alumniPhoto);

    if ($stmt->execute()) {
        // Redirect to pending approval page
        header("Location: ../../pages/alumni/pendingApproval.html");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Helper function to sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}
?>
