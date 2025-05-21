<?php
include('../includes/db_connect.php');
include('../includes/function.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subjectName = $_POST['subjectName'] ?? '';
    $courseName = $_POST['courseName'] ?? '';
    $description = $_POST['description'] ?? '';

    if (empty($subjectName) || empty($courseName)) {
        echo json_encode(['success' => false, 'message' => 'Subject name and course name are required.']);
        exit;
    }

    try {
        $stmt = $conn->prepare('INSERT INTO subjects (subjectName, courseName, description, created_at) VALUES (?, ?, ?, NOW())');
        $stmt->bind_param("sss", $subjectName, $courseName, $description);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Subject added successfully']);
    } catch (mysqli_sql_exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>