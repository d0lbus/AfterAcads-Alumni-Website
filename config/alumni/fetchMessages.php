<?php
include 'connection.php';

$logged_in_user_id = $_GET['logged_in_user_id'];
$friend_id = $_GET['friend_id'];

$stmt = $conn->prepare("
    SELECT sender_id, receiver_id, message, created_at 
    FROM messages 
    WHERE (sender_id = ? AND receiver_id = ?) 
       OR (sender_id = ? AND receiver_id = ?)
    ORDER BY created_at ASC
");
$stmt->bind_param("iiii", $logged_in_user_id, $friend_id, $friend_id, $logged_in_user_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
