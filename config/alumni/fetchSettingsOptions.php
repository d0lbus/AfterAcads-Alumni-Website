// References:
// 1. W3Schools - For querying databases using MySQLi, executing SQL queries, and fetching results in PHP. (https://www.w3schools.com/php/php_mysql_select.asp)
// 2. GeeksforGeeks - For using prepared statements and handling results from MySQL queries in PHP. (https://www.geeksforgeeks.org/php-mysqli-prepared-statements-with-mysqli/)
// 3. StackOverflow - For best practices in handling MySQLi prepared statements and retrieving results from queries. (https://stackoverflow.com/questions/44125469/how-to-fetch-all-rows-with-mysqli)
// 4. YouTube - For video tutorials on PHP MySQL and fetching results using prepared statements:
//    - [PHP MySQL Fetch Results Tutorial](https://www.youtube.com/watch?v=VQip3BdFBh8)
// 5. ChatGPT - For generating PHP code for executing queries and fetching results from MySQL databases.
// 6. PHP Manual - For the `mysqli_query()`, `mysqli_fetch_all()`, and `mysqli_prepare()` functions: (https://www.php.net/manual/en/book.mysqli.php)

<?php
include '../../config/general/connection.php';
include '../../config/alumni/header.php';

$user = getAuthenticatedUser();

// Fetch schools
$schools_query = "SELECT id, name FROM schools WHERE id != 1";
$schools_result = $conn->query($schools_query);
$schools = $schools_result ? $schools_result->fetch_all(MYSQLI_ASSOC) : [];

// Fetch batches
$batches_query = "SELECT batch_number FROM batches WHERE id != 1";
$batches_result = $conn->query($batches_query);
$batches = $batches_result ? $batches_result->fetch_all(MYSQLI_ASSOC) : [];

$user_batch_number = null;
if ($user['batch_id']) {
    $stmt = $conn->prepare("SELECT batch_number FROM batches WHERE id = ?");
    $stmt->bind_param('i', $user['batch_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $batch = $result->fetch_assoc();
    if ($batch) {
        $user_batch_number = $batch['batch_number'];
    }
}


// Return JSON
echo json_encode([
    'selectedBatchNumber' => $user_batch_number,
    'schools' => $schools,
    'batches' => $batches,
]);
?>
