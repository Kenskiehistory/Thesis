<?php
// api/get_announcements_student.php

include('../includes/db_connect.php');
include('../includes/function.php');
session_start();

$user_id = $_SESSION['id'];
$user_role = $_SESSION['roles'];

$section_id = null;
$courseName = null;

if ($user_role == 'Staff') {
    // Get the staff user's section_id and courseName
    $stmt = $conn->prepare('SELECT section_id, courseName FROM staff WHERE user_id = ?');
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        $section_id = $user_data['section_id'];
        $courseName = $user_data['courseName'];
    }
    $stmt->close();
} elseif ($user_role == 'Student') {
    // Get the student user's section_id and courseName
    $stmt = $conn->prepare('SELECT section_id, courseName FROM user_profile WHERE user_id = ?');
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        $section_id = $user_data['section_id'];
        $courseName = $user_data['courseName'];
    }
    $stmt->close();
}

// Fetch announcements for the user's section and general announcements
$stmt = $conn->prepare('
    SELECT id, title, courseName, section_id, created_at
    FROM announcements 
    WHERE (section_id IS NULL OR section_id = ?) 
    AND (courseName = ? OR courseName IS NULL)
    ORDER BY created_at DESC
');
$stmt->bind_param("is", $section_id, $courseName);
$stmt->execute();
$result = $stmt->get_result();
$announcements = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode(['announcements' => $announcements]);
?>
