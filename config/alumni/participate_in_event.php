<?php
session_start();
include '../../config/general/connection.php';

// Validate user session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);

$event_id = isset($input['event_id']) ? intval($input['event_id']) : null;
$status = isset($input['status']) ? $input['status'] : null;

// Validate input
if (!$event_id || ($status !== null && !in_array($status, ['going', 'interested']))) {
    echo json_encode(['success' => false, 'message' => 'Invalid data.']);
    exit;
}

// Cancel participation
if ($status === null) {
    $sql = "DELETE FROM event_participants WHERE event_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $event_id, $user_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Participation removed.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove participation.']);
    }
    exit;
}

// Check if user already participated
$stmt = $conn->prepare("SELECT id FROM event_participants WHERE event_id = ? AND user_id = ?");
$stmt->bind_param("ii", $event_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update participation
    $stmt = $conn->prepare("UPDATE event_participants SET status = ? WHERE event_id = ? AND user_id = ?");
    $stmt->bind_param("sii", $status, $event_id, $user_id);
} else {
    // Insert new participation
    $stmt = $conn->prepare("INSERT INTO event_participants (event_id, user_id, status) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $event_id, $user_id, $status);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Participation updated.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update participation.']);
}
?>
