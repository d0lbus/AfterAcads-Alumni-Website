// References:
// 1. PHP Manual - For handling database connections and queries. (https://www.php.net/manual/en/mysqli.quickstart.php)
// 2. ChatGPT - For explaining how to structure and use custom classes like `FriendsManager` to manage relationships.
// 3. PHP Manual - For handling POST requests and user input sanitization. (https://www.php.net/manual/en/reserved.variables.post.php)

<?php
include '../../config/header.php';
include '../../config/general/connection.php';
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
