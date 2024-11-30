<?php
include '../../config/header.php';
include '../../config/connection.php';
include '../../config/friendsManager.php';

$user = getAuthenticatedUser();

$friendsManager = new FriendsManager($conn);
$logged_in_user_id = $user['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $friend_id = intval($_POST['friend_id']); 

    $success = $friendsManager->removeFriend($logged_in_user_id, $friend_id);

    if ($success) {
        echo json_encode([
            'success' => true,
            'message' => 'Friend removed successfully.',
            'new_button_text' => 'Add Friend'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to remove friend.'
        ]);
    }
    exit();
}
?>
