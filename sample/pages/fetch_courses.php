<?php
include('../includes/db_connect.php');

$result = $conn->query("SELECT * FROM course_reviews");
$courses = [];

while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

echo json_encode($courses);
$conn->close();
?>
