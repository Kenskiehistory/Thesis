<?php
include('../includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseName = $_POST['courseName'];
    $reviewSchedule = $_POST['reviewSchedule'];
    $tuitionFee = $_POST['tuitionFee'];

    $stmt = $conn->prepare("INSERT INTO course_reviews (coursename, reviews, tuition_fee, review_status) VALUES (?, ?, ?, 'Active')");
    $stmt->bind_param('ssd', $courseName, $reviewSchedule, $tuitionFee);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error inserting data']);
    }

    $stmt->close();
    $conn->close();
}
?>
