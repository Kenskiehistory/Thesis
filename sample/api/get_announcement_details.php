<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../includes/db_connect.php');
include('../includes/function.php');

header('Content-Type: application/json');

$announcement_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($announcement_id <= 0) {
    echo json_encode(['error' => 'Invalid announcement ID']);
    exit;
}

try {
    // Fetch announcement details
    $stmt = $conn->prepare('SELECT id, title, content, courseName, created_at, created_by FROM announcements WHERE id = ?');
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $announcement_id);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    $result = $stmt->get_result();
    $announcement = $result->fetch_assoc();
    $stmt->close();

    if (!$announcement) {
        echo json_encode(['error' => 'Announcement not found']);
        exit;
    }

    echo json_encode($announcement);
} catch (Exception $e) {
    error_log("Error in get_announcement_details.php: " . $e->getMessage());
    echo json_encode(['error' => 'An error occurred while fetching the announcement details']);
}
?>