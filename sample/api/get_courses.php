<?php
include('../includes/db_connect.php');

$courses = [];
if ($result = $conn->query('SELECT DISTINCT courseName FROM payment_fees')) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row['courseName'];
    }
    $result->free();
}

header('Content-Type: application/json');
echo json_encode($courses);
?>