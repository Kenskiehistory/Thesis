<?php
// Include necessary files
include('../includes/db_connect.php');
include('../includes/function.php');
secure();

// Get the student's user ID and course name from the session
$user_id = $_SESSION['id'];
$courseName = null;

// Fetch the student's course name
$stmt = $conn->prepare('SELECT courseName FROM user_profile WHERE user_id = ?');
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    $courseName = $user_data['courseName'];
} else {
    echo json_encode(['error' => 'Student course not found']);
    exit();
}

$stmt->close();

// Fetch announcements for the student's course and general announcements
$stmt = $conn->prepare('
    SELECT id, title, content, courseName, section_id, created_at
    FROM announcements 
    WHERE (courseName = ? OR courseName IS NULL)
    ORDER BY created_at DESC
');
$stmt->bind_param("s", $courseName);
$stmt->execute();
$result = $stmt->get_result();
$announcements = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Add a unique identifier to each announcement
foreach ($announcements as &$announcement) {
    $announcement['uniqueId'] = $announcement['id']; // Use the existing 'id' as the unique identifier
}

// Return the announcements as JSON
echo json_encode($announcements);
?>