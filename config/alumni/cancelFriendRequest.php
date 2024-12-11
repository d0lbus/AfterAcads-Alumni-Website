// References:
// 1. W3Schools - For PHP basics, MySQL integration, and header redirection. (https://www.w3schools.com/php/php_mysql_intro.asp)
// 2. StackOverflow - For input validation and handling redirection. (https://stackoverflow.com/questions/5637279/how-to-redirect-in-php)
// 3. GeeksforGeeks - For error handling and general PHP tips. (https://www.geeksforgeeks.org/how-to-handle-errors-in-php/)
// 4. YouTube - For video tutorials on PHP and MySQL basics. (Search "PHP MySQL tutorial" on YouTube)
// 5. ChatGPT - For generating and explaining general PHP concepts and best practices.

<?php
include 'header.php';
include '../../config/general/connection.php';
include 'friendsManager.php';

$user = getAuthenticatedUser();

$friendsManager = new FriendsManager($conn);
$logged_in_user_id = $user['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $friend_id = intval($_POST['friend_id']);

    // Cancel the friend request
    $success = $friendsManager->cancelFriendRequest($logged_in_user_id, $friend_id);

    if ($success) {
        header("Location: ../../pages/alumni/viewProfile.php?user_id=$friend_id");
    } else {
        echo "Failed to cancel friend request.";
    }
    exit();
}
?>
