// approve_user.php
<?php
session_start();
include '../../config/general/connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['user_id'], $_POST['action'])) {
    $userId = intval($_POST['user_id']);
    $action = $_POST['action'];

    if ($action === "Approve") {
        $status = 'approved';
    } elseif ($action === "Reject") {
        $status = 'rejected';
    } else {
        die("Invalid action.");
    }

    $sql = "UPDATE users SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $userId);
    
    if ($stmt->execute()) {
        echo "User successfully {$action}d.";
    } else {
        echo "Error in {$action}ing user.";
    }
}
?>
