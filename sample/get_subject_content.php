<?php
header('Content-Type: application/json');
session_start();
require_once '../includes/db_connect.php';

// Error handling function
function return_error($message) {
    echo json_encode(['error' => $message]);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    return_error('User not logged in');
}

$user_id = $_SESSION['id'];
$subject_id = isset($_GET['subject_id']) ? intval($_GET['subject_id']) : 0;

if ($subject_id === 0) {
    return_error('Invalid subject ID');
}

try {
    // Get user's course name
    $stmt = $conn->prepare('SELECT courseName FROM user_profile WHERE user_id = ?');
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return_error('User profile not found');
    }

    $user_data = $result->fetch_assoc();
    $user_course = $user_data['courseName'];

    // Get subject details and verify it belongs to the user's course
    $stmt = $conn->prepare('SELECT * FROM subjects WHERE id = ? AND courseName = ?');
    $stmt->bind_param("is", $subject_id, $user_course);
    $stmt->execute();
    $subject = $stmt->get_result()->fetch_assoc();

    if (!$subject) {
        return_error('Subject not found or you don\'t have access to this subject');
    }

    // Get subject contents
    $stmt = $conn->prepare('SELECT * FROM subject_content WHERE subject_id = ? ORDER BY created_at DESC');
    $stmt->bind_param("i", $subject_id);
    $stmt->execute();
    $contents = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    echo json_encode(['subject' => $subject, 'contents' => $contents]);
} catch (Exception $e) {
    return_error('Database error: ' . $e->getMessage());
}