// References:
// 1. W3Schools - For PHP MySQL `JOIN` operations and prepared statements. (https://www.w3schools.com/php/php_mysql_join.asp)
// 2. StackOverflow - For handling SQL queries and fetching data using prepared statements. (https://stackoverflow.com/questions/11324991/how-to-prepare-a-select-query-in-php-with-mysqli)
// 3. GeeksforGeeks - For working with prepared statements and `bind_param` method in MySQLi. (https://www.geeksforgeeks.org/php-mysqli-prepared-statements/)
// 4. YouTube - For video tutorials on PHP, MySQL, and handling MySQL queries:
//    - [PHP MySQL Join Tutorial](https://www.youtube.com/watch?v=tm0VsGHkCVY)
//    - [PHP MySQLi Prepared Statements Tutorial](https://www.youtube.com/watch?v=eJXxKuyjU2A)
//    - [PHP MySQL Fetch Data Example](https://www.youtube.com/watch?v=l_hT8Z4FUbQ)
// 5. ChatGPT - For generating code for handling SQL queries, preparing statements, and handling data in PHP.
// 6. PHP Manual - For details on `mysqli_prepare`, `mysqli_bind_param`, and `mysqli_execute` functions. (https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php)

<?php
include '../../config/general/connection.php';

$postId = $_GET['post_id'] ?? null;

if (!$postId) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT comments.*, users.first_name, users.last_name 
        FROM comments 
        JOIN users ON comments.user_id = users.id 
        WHERE comments.post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $postId);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];
while ($row = $result->fetch_assoc()) {
    $comments[] = [
        "user_name" => $row['first_name'] . ' ' . $row['last_name'],
        "comment" => $row['comment'],
        "created_at" => $row['created_at']
    ];
}

echo json_encode($comments);

$stmt->close();
$conn->close();
?>
