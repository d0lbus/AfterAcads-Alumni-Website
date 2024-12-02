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

// Validate input data
if (!$event_id || ($status !== null && !in_array($status, ['going', 'interested']))) {
    echo json_encode(['success' => false, 'message' => 'Invalid data.']);
    exit;
}

if ($status === null) {
    // Remove participation if the status is null (user cancels)
    $sql = "DELETE FROM event_participants WHERE event_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $event_id, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Participation removed.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove participation.']);
    }
    exit();
}

// Check if the user already participated in the event
$stmt = $conn->prepare("SELECT id FROM event_participants WHERE event_id = ? AND user_id = ?");
$stmt->bind_param("ii", $event_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update existing participation
    $stmt = $conn->prepare("UPDATE event_participants SET status = ? WHERE event_id = ? AND user_id = ?");
    $stmt->bind_param("sii", $status, $event_id, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Participation updated to ' . $status . '.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update participation.']);
    }
} else {
    // Insert new participation
    $stmt = $conn->prepare("INSERT INTO event_participants (event_id, user_id, status) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $event_id, $user_id, $status);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Participation added as ' . $status . '.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add participation.']);
    }
}
?>
