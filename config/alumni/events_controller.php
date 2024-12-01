<?php
// events_controller.php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['email'])) {
  header("Location: loginpage.php");
  exit();
}

include '../config/general/connection.php'; // Database connection

// Fetch the logged-in user's details from the database
$email = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $user = $result->fetch_assoc(); // Fetch user data
} else {
  echo "Error: User not found.";
  exit();
}

// Pagination and Filter Variables
$events_per_page = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$search = isset($_GET['search']) ? $_GET['search'] : null;
$tag = isset($_GET['tag']) ? $_GET['tag'] : null;
$offset = ($page - 1) * $events_per_page;

// Base SQL query
$sql = "SELECT id, title, description, image_path, alt_text, tag FROM events WHERE 1=1";

// Append tag filter if selected
if ($tag) {
    $sql .= " AND tag = ?";
}

// Append search filter if provided
if ($search) {
    $sql .= " AND (title LIKE ? OR description LIKE ?)";
}

// Count total number of events for pagination
$sql_count = str_replace("id, title, description, image_path, alt_text, tag", "COUNT(*) AS total_events", $sql);
$stmt_count = $conn->prepare($sql_count);
if ($tag && $search) {
    $search_term = '%' . $search . '%';
    $stmt_count->bind_param("sss", $tag, $search_term, $search_term);
} elseif ($tag) {
    $stmt_count->bind_param("s", $tag);
} elseif ($search) {
    $search_term = '%' . $search . '%';
    $stmt_count->bind_param("ss", $search_term, $search_term);
}
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$row_count = $result_count->fetch_assoc();
$total_events = $row_count['total_events'];
$total_pages = ceil($total_events / $events_per_page);

// Append pagination using LIMIT and OFFSET
$sql .= " LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
if ($tag && $search) {
    $stmt->bind_param('ssiii', $tag, $search_term, $search_term, $events_per_page, $offset);
} elseif ($tag) {
    $stmt->bind_param('sii', $tag, $events_per_page, $offset);
} elseif ($search) {
    $stmt->bind_param('ssii', $search_term, $search_term, $events_per_page, $offset);
} else {
    $stmt->bind_param('ii', $events_per_page, $offset);
}
$stmt->execute();
$result = $stmt->get_result();
