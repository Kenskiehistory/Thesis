<?php
include('../includes/db_connect.php');
include('../includes/function.php');
header('Content-Type: application/json');

$selectedCourse = isset($_GET['course']) ? $_GET['course'] : '';

// Prepare the SQL query based on the filter
$sql = 'SELECT id, title, courseName, created_at, created_by FROM announcements';
$params = [];
$types = '';

if (!empty($selectedCourse)) {
    $sql .= ' WHERE courseName = ?';
    $params[] = $selectedCourse;
    $types .= 's';
}

$sql .= ' ORDER BY created_at DESC';

// Fetch announcements
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$announcements = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch all unique course names
$stmt = $conn->prepare('SELECT DISTINCT courseName FROM announcements WHERE courseName IS NOT NULL');
$stmt->execute();
$result = $stmt->get_result();
$courses = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

echo json_encode([
    'announcements' => $announcements,
    'courses' => $courses
]);
?>