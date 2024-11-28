<?php

// source: https://stackoverflow.com/questions/2542800/how-to-make-an-add-friend-defriend-function-in-php
// source: https://www.youtube.com/watch?v=Rf4gFHhUaz4

class friendsManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Send a friend request
    public function sendFriendRequest($user1, $user2) {
        // Check if the relationship already exists
        $stmt = $this->conn->prepare("
            SELECT * FROM friends WHERE (user1 = ? AND user2 = ?) OR (user1 = ? AND user2 = ?)
        ");
        $stmt->bind_param("iiii", $user1, $user2, $user2, $user1);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return "You already have a relationship with this user.";
        }

        // Insert a new friend request
        $stmt = $this->conn->prepare("INSERT INTO friends (user1, user2, accepted) VALUES (?, ?, 0)");
        $stmt->bind_param("ii", $user1, $user2);
        if ($stmt->execute()) {
            return "Friend request sent successfully.";
        } else {
            return "Error: " . $this->conn->error;
        }
    }

    // Accept a friend request
    public function acceptFriendRequest($user1, $user2) {
        $stmt = $this->conn->prepare("UPDATE friends SET accepted = 1 WHERE user1 = ? AND user2 = ?");
        $stmt->bind_param("ii", $user1, $user2);
        if ($stmt->execute()) {
            return "Friend request accepted.";
        } else {
            return "Error: " . $this->conn->error;
        }
    }

    // Unfriend a user
    public function unfriend($user1, $user2) {
        $stmt = $this->conn->prepare("DELETE FROM friends WHERE (user1 = ? AND user2 = ?) OR (user1 = ? AND user2 = ?)");
        $stmt->bind_param("iiii", $user1, $user2, $user2, $user1);
        if ($stmt->execute()) {
            return "Unfriended successfully.";
        } else {
            return "Error: " . $this->conn->error;
        }
    }

    // Check friend status
    public function checkFriendStatus($user1, $user2) {
        $stmt = $this->conn->prepare("
            SELECT * FROM friends 
            WHERE (user1 = ? AND user2 = ?) OR (user1 = ? AND user2 = ?)
        ");
        $stmt->bind_param("iiii", $user1, $user2, $user2, $user1);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['accepted'] == 1) {
                return 'friends';
            } elseif ($row['user1'] == $user1) {
                return 'pending';
            } else {
                return 'pending';
            }
        } else {
            return 'not_friends';
        }
    }

    // Fetch all friends for a user
    public function getFriends($userId) {
        $stmt = $this->conn->prepare("
            SELECT 
                CASE 
                    WHEN user1 = ? THEN user2
                    ELSE user1
                END AS friend_id
            FROM friends
            WHERE (user1 = ? OR user2 = ?) AND accepted = 1
        ");
        $stmt->bind_param("iii", $userId, $userId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $friends = [];
        while ($row = $result->fetch_assoc()) {
            $friends[] = $row['friend_id'];
        }
        return $friends;
    }
}
?>
