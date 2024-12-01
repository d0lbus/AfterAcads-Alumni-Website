<?php
include 'header.php';
include '../config/general/connection.php';
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
