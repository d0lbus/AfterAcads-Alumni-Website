// References:
// 1. W3Schools - For PHP basics, MySQL integration, and prepared statements. (https://www.w3schools.com/php/php_mysql_intro.asp)
// 2. StackOverflow - For input validation and error handling. (https://stackoverflow.com/questions/6031904/how-to-validate-and-filter-user-input-in-php)
// 3. GeeksforGeeks - For error handling and general PHP tips. (https://www.geeksforgeeks.org/how-to-handle-errors-in-php/)
// 4. YouTube - For video tutorials on PHP and MySQL basics:
//    - [PHP & MySQL Database Tutorial](https://www.youtube.com/watch?v=4z8hgtbrO5k)
//    - [PHP MySQL Prepared Statements Tutorial](https://www.youtube.com/watch?v=dh52_1Vvwgg)
//    - [PHP MySQL Query Filter Tutorial](https://www.youtube.com/watch?v=TKl-WnpV9WA)
//    - [PHP and MySQL Tutorial for Beginners](https://www.youtube.com/watch?v=J6wv9rfVs2I)
//    - [PHP MySQL CRUD Tutorial](https://www.youtube.com/watch?v=ZTthk9M4_fM)
// 5. ChatGPT - For generating and explaining general PHP concepts and best practices.
// approve_user.php

<?php
session_start();
include '../../config/general/connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['user_id'], $_POST['action'])) {
    $userId = intval($_POST['user_id']);
    $action = $_POST['action'];

    if ($action === "Approve") {
        $status = 'approved';
    } elseif ($action === "Reject") {
        $status = 'rejected';
    } else {
        die("Invalid action.");
    }

    $sql = "UPDATE users SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $userId);
    
    if ($stmt->execute()) {
        echo "User successfully {$action}d.";
    } else {
        echo "Error in {$action}ing user.";
    }
}
?>
