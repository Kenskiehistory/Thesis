<?php
include('../includes/config.php');
include('../includes/db_connect.php');
include('../includes/function.php');
secure();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['courseName'], $_POST['title'], $_POST['content'],)) {
    $courseName = $_POST['courseName'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    // Retrieve the role from the session
    $created_by = isset($_SESSION['roles']) ? $_SESSION['roles'] : 'Unknown';

    $stmt = $conn->prepare('INSERT INTO announcements (courseName, title, content, created_by) VALUES (?, ?, ?, ?)');
    $stmt->bind_param("ssss", $courseName, $title, $content, $created_by);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Announcement posted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error posting announcement: ' . $conn->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}