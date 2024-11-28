<?php
include 'header.php';
include 'connection.php';
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
