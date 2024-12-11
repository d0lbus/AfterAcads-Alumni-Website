// References:
// 1. PHP Manual - For handling sessions in PHP. (https://www.php.net/manual/en/function.session-start.php)
// 2. PHP Manual - For `mysqli_prepare()` and `mysqli_bind_param()` functions used in prepared statements. (https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php)
// 3. W3Schools - For handling JSON input with `json_decode()` in PHP. (https://www.w3schools.com/php/func_json_decode.asp)
// 4. ChatGPT - For handling SQL operations (insert, update, delete) based on conditions in a database, such as adding, updating, or removing event participations. 
//    (General programming best practices for handling dynamic user input and database updates)
// 5. StackOverflow - For troubleshooting issues related to user session management and parameterized queries in PHP. (https://stackoverflow.com/questions/14597514/how-to-handle-mysqli-error)
// 6. YouTube - For video tutorials on PHP session management and working with MySQL in PHP: [PHP MySQL Tutorials](https://www.youtube.com/results?search_query=php+mysql+tutorial)

<?php
include '../../config/general/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_id = $_POST['sender_id'];
    $receiver_id = $_POST['receiver_id'];
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
        $stmt->execute();
        $stmt->close();

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Message cannot be empty.']);
    }
}
?>
