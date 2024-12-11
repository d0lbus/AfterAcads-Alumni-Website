// References:
// 1. W3Schools - For handling sessions in PHP. (https://www.w3schools.com/php/php_sessions.asp)
// 2. PHP Manual - For `mysqli_prepare()` and `mysqli_bind_param()` functions used in prepared statements. (https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php)
// 3. StackOverflow - For troubleshooting SQL queries and handling user authentication in PHP. (https://stackoverflow.com/questions/14597514/how-to-handle-mysqli-error)
// 4. GeeksforGeeks - For understanding SQL query building with dynamic conditions in PHP. (https://www.geeksforgeeks.org/dynamic-queries-in-php/)
// 5. YouTube - For PHP session management tutorials: [PHP Sessions and Authentication](https://www.youtube.com/watch?v=9PH_0_6sI6I)

<?php
session_start();
include '../../config/general/connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

header('Content-Type: application/json');

// Handle different requests
if (isset($_GET['load'])) {
    switch ($_GET['load']) {
        case 'schools':
            // Fetch all schools
            $query = "SELECT id, name FROM schools";
            $result = $conn->query($query);
            $schools = [];
            while ($row = $result->fetch_assoc()) {
                $schools[] = $row;
            }
            echo json_encode(['success' => true, 'schools' => $schools]);
            break;

        case 'courses':
            // Fetch courses for a specific school
            $school_id = intval($_GET['school_id']);
            $query = "SELECT id, name FROM courses WHERE school_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $school_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $courses = [];
            while ($row = $result->fetch_assoc()) {
                $courses[] = $row;
            }
            echo json_encode(['success' => true, 'courses' => $courses]);
            break;

        case 'opportunities':
            // Fetch opportunities based on filters
            $school_id = isset($_GET['school_id']) ? intval($_GET['school_id']) : null;
            $course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : null;

            $sql = "
                SELECT 
                    opportunities.*, 
                    courses.name AS course_name 
                FROM opportunities
                LEFT JOIN courses ON opportunities.course_id = courses.id
                WHERE 1=1
            ";

            $params = [];
            $types = "";

            if ($school_id) {
                $sql .= " AND opportunities.school_id = ?";
                $params[] = $school_id;
                $types .= "i";
            }

            if ($course_id) {
                $sql .= " AND opportunities.course_id = ?";
                $params[] = $course_id;
                $types .= "i";
            }

            $stmt = $conn->prepare($sql);

            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            $opportunities = [];
            while ($row = $result->fetch_assoc()) {
                $opportunities[] = $row;
            }

            echo json_encode(['success' => true, 'opportunities' => $opportunities]);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No valid load parameter provided.']);
}
?>
