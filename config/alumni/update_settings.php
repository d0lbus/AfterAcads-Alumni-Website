// References:
// 1. PHP Manual - File Uploads and Validation (https://www.php.net/manual/en/features.file-upload.php)
// 2. ChatGPT - For handling dynamic SQL queries and user input sanitization.
// 3. PHP Manual - Session Management (https://www.php.net/manual/en/book.session.php)

<?php
include '../../config/general/connection.php';
include '../../config/alumni/header.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user ID from the session
    if (!isset($_SESSION['user_id'])) {
        die(json_encode(['success' => false, 'message' => 'User not logged in.']));
    }
    $user_id = $_SESSION['user_id'];

    // Handle profile picture upload
    $profilePicture = null;
    if (!empty($_FILES['profile-picture']['tmp_name'])) {
        $file = $_FILES['profile-picture'];
        $allowedTypes = ['image/jpeg', 'image/png'];
    
        if (in_array($file['type'], $allowedTypes)) {
            if ($file['size'] <= 2 * 1024 * 1024) { // 2MB max
                $profilePicture = file_get_contents($file['tmp_name']);
            } else {
                die(json_encode(['success' => false, 'message' => 'Profile picture exceeds 2MB limit.']));
            }
        } else {
            die(json_encode(['success' => false, 'message' => 'Invalid file format. Only JPG and PNG are allowed.']));
        }
    }
    
    if ($profilePicture) {
        error_log('Profile picture received: ' . substr(base64_encode($profilePicture), 0, 100)); // Log part of the encoded image
    } else {
        error_log('Profile picture is null.');
    }

    // Get other form fields
    $first_name = $_POST['first-name'] ?? null;
    $middle_name = $_POST['middle-name'] ?? null;
    $last_name = $_POST['last-name'] ?? null;
    $bio = $_POST['bio'] ?? null;
    $address = $_POST['address'] ?? null;
    $school_id = !empty($_POST['school']) ? intval($_POST['school']) : null;
    $course_id = !empty($_POST['course']) ? intval($_POST['course']) : null;
    $batch_number = !empty($_POST['batch']) ? intval($_POST['batch']) : null;
    $employment_status = $_POST['employment-status'] ?? null;


    // Map batch number to batch ID
    $batch_id = null;
    if ($batch_number) {
        $stmt = $conn->prepare("SELECT id FROM batches WHERE batch_number = ?");
        $stmt->bind_param('i', $batch_number);
        $stmt->execute();
        $result = $stmt->get_result();
        $batch = $result->fetch_assoc();
        if ($batch) {
            $batch_id = $batch['id'];
        } else {
            die(json_encode(['success' => false, 'message' => 'Invalid batch number.']));
        }
    }

    // Handle password change
    $new_password = $_POST['new-password'] ?? null;
    $confirm_password = $_POST['confirm-password'] ?? null;
    $password_hash = null;
    if (!empty($new_password) && !empty($confirm_password)) {
        if ($new_password === $confirm_password) {
            $password_hash = password_hash($new_password, PASSWORD_BCRYPT);
        } else {
            die(json_encode(['success' => false, 'message' => 'Passwords do not match.']));
        }
    }

    $query = "UPDATE users SET 
            first_name = ?, 
            middle_name = ?, 
            last_name = ?, 
            bio = ?, 
            user_address = ?, 
            school_id = ?, 
            course_id = ?, 
            batch_id = ?, 
            employment_status = ?"; 
    $params = [$first_name, $middle_name, $last_name, $bio, $address, $school_id, $course_id, $batch_id, $employment_status];
    $types = "sssssiiis"; 

    if ($profilePicture) {
        $query .= ", profile_picture = ?";
        $params[] = $profilePicture;
        $types .= "b";
    }
    if ($password_hash) {
        $query .= ", password_hash = ?";
        $params[] = $password_hash;
        $types .= "s";
    }

    $query .= " WHERE id = ?";
    $params[] = $user_id;
    $types .= "i";


    $stmt = $conn->prepare($query);

    error_log("Query: $query");
    error_log("Params: " . json_encode($params));


    // Bind parameters dynamically
    $bind_names = [];
    foreach ($params as $key => $value) {
        $bind_name = 'bind' . $key;
        $$bind_name = $value; // Dynamically create variable
        $bind_names[$key] = &$$bind_name; // Pass by reference
    }
    array_unshift($bind_names, $types);
    call_user_func_array([$stmt, 'bind_param'], $bind_names);

    // Bind binary data for the profile picture explicitly
    if ($profilePicture) {
        $stmt->send_long_data(count($params) - 2, $profilePicture); // Position of profile_picture
    }

    if ($stmt->execute()) {
        header("Location: ../../pages/alumni/settings.php?success=true");
        exit();
    } else {
        die(json_encode(['success' => false, 'message' => 'Failed to update settings: ' . $stmt->error]));
    }
}
?>
