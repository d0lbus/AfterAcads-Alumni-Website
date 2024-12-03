<?php
include '../../config/general/connection.php';
include '../../config/alumni/header.php';

$user = getAuthenticatedUser();

// Fetch schools
$schools_query = "SELECT id, name FROM schools WHERE id != 1";
$schools_result = $conn->query($schools_query);
$schools = $schools_result ? $schools_result->fetch_all(MYSQLI_ASSOC) : [];

// Fetch batches
$batches_query = "SELECT batch_number FROM batches WHERE id != 1";
$batches_result = $conn->query($batches_query);
$batches = $batches_result ? $batches_result->fetch_all(MYSQLI_ASSOC) : [];

$user_batch_number = null;
if ($user['batch_id']) {
    $stmt = $conn->prepare("SELECT batch_number FROM batches WHERE id = ?");
    $stmt->bind_param('i', $user['batch_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $batch = $result->fetch_assoc();
    if ($batch) {
        $user_batch_number = $batch['batch_number'];
    }
}


// Return JSON
echo json_encode([
    'selectedBatchNumber' => $user_batch_number,
    'schools' => $schools,
    'batches' => $batches,
]);
?>
