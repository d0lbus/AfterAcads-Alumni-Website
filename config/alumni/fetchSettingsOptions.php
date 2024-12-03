<?php
include '../../config/general/connection.php';

// Fetch schools
$schools_query = "SELECT id, name FROM schools WHERE id != 1";
$schools_result = $conn->query($schools_query);
$schools = $schools_result ? $schools_result->fetch_all(MYSQLI_ASSOC) : [];

// Fetch batches
$batches_query = "SELECT batch_number FROM batches WHERE id != 1";
$batches_result = $conn->query($batches_query);
$batches = $batches_result ? $batches_result->fetch_all(MYSQLI_ASSOC) : [];

// Return JSON
echo json_encode([
    'schools' => $schools,
    'batches' => $batches,
]);
?>
