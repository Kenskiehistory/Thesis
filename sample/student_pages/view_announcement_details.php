<?php
include('../includes/db_connect.php');
include('../includes/function.php');
secure();

// Get the announcement ID from the GET request
$announcement_id = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($announcement_id)) {
    echo json_encode(['error' => 'Announcement ID is required.']);
    exit();
}

// Fetch announcement details
$stmt = $conn->prepare('SELECT title, content, courseName, created_at, created_by FROM announcements WHERE id = ?');
$stmt->bind_param("i", $announcement_id);
$stmt->execute();
$result = $stmt->get_result();
$announcement = $result->fetch_assoc();
$stmt->close();

if (!$announcement) {
    echo json_encode(['error' => 'Announcement not found.']);
    exit();
}


// Return the announcement details as JSON
echo json_encode($announcement);
?>