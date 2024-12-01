<?php
include '../../config/general/connection.php';

$sql = "SELECT id, batch_number FROM batches";
$result = $conn->query($sql);

$batches = [];
while ($row = $result->fetch_assoc()) {
    $batches[] = $row;
}

echo json_encode($batches);

$conn->close();
?>
