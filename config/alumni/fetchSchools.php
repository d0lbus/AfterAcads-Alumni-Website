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

header('Content-Type: application/json');

// Fetch all schools from the database
$sql = "SELECT id, name FROM schools ORDER BY name ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $schools = [];
    while ($row = $result->fetch_assoc()) {
        $schools[] = $row;
    }
    echo json_encode($schools);
} else {
    echo json_encode([]);
}

$conn->close();
?>
