<?php
require_once 'connection.php';
require_once 'friendsManager.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to unfriend someone.");
}

$logged_in_user_id = $_SESSION['user_id'];
$friend_id = intval($_POST['friend_id']);

$friendsManager = new FriendsManager($conn);
echo $friendsManager->unfriend($logged_in_user_id, $friend_id);
?>
