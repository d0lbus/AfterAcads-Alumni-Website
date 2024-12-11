// References:
// 1. W3Schools - For PHP basics, MySQL integration, and prepared statements. (https://www.w3schools.com/php/php_mysql_intro.asp)
// 2. StackOverflow - For input validation and error handling. (https://stackoverflow.com/questions/6031904/how-to-validate-and-filter-user-input-in-php)
// 3. GeeksforGeeks - For error handling and general PHP tips. (https://www.geeksforgeeks.org/how-to-handle-errors-in-php/)
// 4. YouTube - For video tutorials on PHP and MySQL basics. (Search "PHP MySQL tutorial" on YouTube)
// 5. ChatGPT - For generating and explaining general PHP concepts and best practices.
// 6. PHP Manual - For details on the `json_encode` function. (https://www.php.net/manual/en/function.json-encode.php)

<?php
include 'header.php';
include '../config/general/connection.php';
include 'friendsManager.php';

$user = getAuthenticatedUser();

$friendsManager = new FriendsManager($conn);
$logged_in_user_id = $user['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure 'friend_id' is sent in the POST request
    if (!isset($_POST['friend_id']) || empty($_POST['friend_id'])) {
        die("Friend ID is required.");
    }

    $friend_id = intval($_POST['friend_id']); // Sanitize input

    // Call the FriendsManager to send the friend request
    $success = $friendsManager->sendFriendRequest($logged_in_user_id, $friend_id);

    if ($success) {
        echo json_encode([
            'success' => true,
            'message' => 'Friend request sent successfully.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to send friend request.'
        ]);
    }
    exit();
} else {
    die("Invalid request method.");
}
?>
