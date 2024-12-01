<?php

//source: https://www.youtube.com/watch?v=G9lnP3ZTjkU&list=PLY3j36HMSHNWdM1oRHmFIOLxneqSZ6byi

include 'header.php';
include 'connection.php';

$user = getAuthenticatedUser();
$logged_in_user_id = $user['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $friend_id = intval($_POST['friend_id']); 

    if ($friend_id <= 0) {
        die("Invalid friend ID.");
    }

    header("Location: ../../pages/alumni/sendMessagePage.php?chat_with=" . $friend_id);
    exit();
}


?>