<?php
include('../includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseName = $_POST['courseName'];
    $sectionName = $_POST['sectionName'];
    $sectionLimit = $_POST['sectionLimit'];

    $stmt = $conn->prepare("INSERT INTO sectioning (course_id, section_name, section_limit, section_count) VALUES (?, ?, ?, 0)");
    $stmt->bind_param('isi', $courseName, $sectionName, $sectionLimit);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error inserting data']);
    }

    $stmt->close();
    $conn->close();
}
?>
