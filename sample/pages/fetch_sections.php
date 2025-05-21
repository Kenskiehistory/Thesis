<?php
// Enable error reporting to catch potential issues during development
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); // Ensure the response is JSON format

include('../includes/db_connect.php');

$query = "SELECT s.id, s.section_name, s.section_limit, s.section_count, c.reviews as course_name 
          FROM sectioning s 
          JOIN course_reviews c ON s.course_id = c.id";

$result = $conn->query($query);
$sections = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $sections[] = $row;
    }
    echo json_encode($sections); // Send JSON response
} else {
    echo json_encode(['success' => false, 'message' => 'Error fetching sections']);
}

$conn->close();
?>
