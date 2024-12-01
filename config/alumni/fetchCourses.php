<?php
include 'connection.php';

$school_id = $_GET['school_id'];
$query = "SELECT id, name FROM courses WHERE school_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $school_id);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}
echo json_encode($courses);


$conn->close();
?>
