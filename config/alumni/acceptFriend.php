<?php

/**
 * References:
 * - **YouTube**: "PHP PDO Tutorial for Beginners" by Code Academy: https://www.youtube.com/watch?v=Ey6ZLyBu1oY
 * - **Stack Overflow**: Best practices for handling POST requests securely: https://stackoverflow.com/questions/14565460/handling-post-requests-in-php
 * - **GeeksforGeeks**: "How to Use PHP Header Function": https://www.geeksforgeeks.org/php-header-function/
 * - **W3Schools**: "PHP POST Method Explained": https://www.w3schools.com/php/php_forms.asp
 * - **ChatGPT**: Recommendations for secure coding practices and handling form data.
 */

include 'header.php';
include '../../config/general/connection.php';
include 'friendsManager.php';

$user = getAuthenticatedUser();

$friendsManager = new FriendsManager($conn);
$logged_in_user_id = $user['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $friend_id = intval($_POST['friend_id']);

    // Accept the friend request
    $success = $friendsManager->acceptFriendRequest($friend_id, $logged_in_user_id);

    if ($success) {
        header("Location: ../../pages/alumni/viewProfile.php?user_id=$friend_id");
    } else {
        echo "Failed to accept friend request.";
    }
    exit();
}
?>
