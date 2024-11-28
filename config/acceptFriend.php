<?php
require_once 'connection.php';
require_once 'friendsManager.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to send a friend request.");
}

$logged_in_user_id = $_SESSION['user_id'];
$friend_id = intval($_POST['friend_id']);

$friendsManager = new FriendsManager($conn);
echo $friendsManager->sendFriendRequest($friend_id, $logged_in_user_id);
?>
