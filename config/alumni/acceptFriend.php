<?php
include 'header.php';
include 'connection.php';
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
