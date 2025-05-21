<?php
// Disable error reporting
error_reporting(0);
ini_set('display_errors', 0);

// Set JSON header
header('Content-Type: application/json');

// Custom error handler
function json_error_handler($errno, $errstr, $errfile, $errline) {
    $error = array(
        'error' => 'PHP Error',
        'message' => $errstr,
        'file' => $errfile,
        'line' => $errline
    );
    echo json_encode($error);
    exit;
}

// Set custom error handler
set_error_handler("json_error_handler");

// Wrap the entire script in a try-catch block
try {
    session_start();
    require_once '../includes/db_connect.php';

    // Check if user is logged in
    if (!isset($_SESSION['id'])) {
        throw new Exception('User not logged in');
    }

    $user_id = $_SESSION['id'];

    // Get user's course name
    $stmt = $conn->prepare('SELECT courseName FROM user_profile WHERE user_id = ?');
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('User profile not found');
    }

    $user_data = $result->fetch_assoc();
    $user_course = $user_data['courseName'];

    // Get subjects for the user's course
    $stmt = $conn->prepare('SELECT * FROM subjects WHERE courseName = ?');
    $stmt->bind_param("s", $user_course);
    $stmt->execute();
    $subjects = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Get content counts for each subject
    $stmt = $conn->prepare('
        SELECT subject_id, COUNT(*) as content_count
        FROM subject_contents
        GROUP BY subject_id
    ');
    $stmt->execute();
    $content_counts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $content_count_map = [];
    foreach ($content_counts as $count) {
        $content_count_map[$count['subject_id']] = $count['content_count'];
    }

    // Add content count to each subject
    foreach ($subjects as &$subject) {
        $subject['content_count'] = isset($content_count_map[$subject['id']]) ? $content_count_map[$subject['id']] : 0;
    }

    echo json_encode(['subjects' => $subjects, 'userCourse' => $user_course]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}