// References:
// 1. PHP Manual - For handling sessions in PHP. (https://www.php.net/manual/en/function.session-start.php)
// 2. PHP Manual - For `mysqli_prepare()` and `mysqli_bind_param()` functions used in prepared statements. (https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php)
// 3. W3Schools - For handling JSON input with `json_decode()` in PHP. (https://www.w3schools.com/php/func_json_decode.asp)
// 4. ChatGPT - For handling SQL operations (insert, update, delete) based on conditions in a database, such as adding, updating, or removing event participations. 
//    (General programming best practices for handling dynamic user input and database updates)
// 5. StackOverflow - For troubleshooting issues related to user session management and parameterized queries in PHP. (https://stackoverflow.com/questions/14597514/how-to-handle-mysqli-error)
// 6. YouTube - For video tutorials on PHP session management and working with MySQL in PHP: [PHP MySQL Tutorials](https://www.youtube.com/results?search_query=php+mysql+tutorial)

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
