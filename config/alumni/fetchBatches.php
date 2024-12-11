// References:
// 1. W3Schools - For basic PHP and MySQLi integration, including fetching data with `fetch_assoc()`. (https://www.w3schools.com/php/php_mysql_select.asp)
// 2. GeeksforGeeks - For using MySQL queries with PHP and fetching results using `query()` and `fetch_assoc()`. (https://www.geeksforgeeks.org/php-mysql-select-query-with-mysqli/)
// 3. StackOverflow - For handling SQL queries and working with `mysqli_query()` and `fetch_assoc()`. (https://stackoverflow.com/questions/11573051/mysqli-query-fetch-assoc)
// 4. YouTube - For video tutorials on PHP and MySQL basics:
//    - [PHP MySQL Basic Query Tutorial](https://www.youtube.com/watch?v=DN0i9nfyP4w)
//    - [Working with MySQL and PHP](https://www.youtube.com/watch?v=e02gDN5Ff4g)
// 5. ChatGPT - For generating PHP code to execute a basic SELECT query and return results in JSON format.
// 6. PHP Manual - For detailed usage of `mysqli_query()` and `fetch_assoc()` functions. (https://www.php.net/manual/en/mysqli.query.php)

<?php
include '../../config/general/connection.php';

$sql = "SELECT id, batch_number FROM batches";
$result = $conn->query($sql);

$batches = [];
while ($row = $result->fetch_assoc()) {
    $batches[] = $row;
}

echo json_encode($batches);

$conn->close();
?>
