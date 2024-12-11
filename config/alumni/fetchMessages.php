// References:
// 1. W3Schools - For basic PHP and MySQLi integration, including preparing and binding parameters in SQL queries. (https://www.w3schools.com/php/php_mysql_prepared_statements.asp)
// 2. GeeksforGeeks - For using prepared statements and executing queries in PHP with MySQLi. (https://www.geeksforgeeks.org/php-mysql-prepared-statements-with-mysqli/)
// 3. StackOverflow - For using `prepare()`, `bind_param()`, and `get_result()` with MySQLi in PHP. (https://stackoverflow.com/questions/20737721/php-mysqli-prepared-statements-with-bind-param)
// 4. YouTube - For video tutorials on PHP, MySQL, and prepared statements:
//    - [PHP MySQL Prepared Statements Tutorial](https://www.youtube.com/watch?v=92VdWby0Jrw)
//    - [Using Prepared Statements in PHP with MySQL](https://www.youtube.com/watch?v=VQip3BdFBh8)
// 5. ChatGPT - For generating PHP code to execute a SELECT query with a prepared statement.
// 6. PHP Manual - For using MySQLi prepared statements and binding parameters. (https://www.php.net/manual/en/mysqli.prepare.php)

<?php
include '../../config/general/connection.php';

$logged_in_user_id = $_GET['logged_in_user_id'];
$friend_id = $_GET['friend_id'];

$stmt = $conn->prepare("
    SELECT sender_id, receiver_id, message, created_at 
    FROM messages 
    WHERE (sender_id = ? AND receiver_id = ?) 
       OR (sender_id = ? AND receiver_id = ?)
    ORDER BY created_at ASC
");
$stmt->bind_param("iiii", $logged_in_user_id, $friend_id, $friend_id, $logged_in_user_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
