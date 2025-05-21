<?php
require_once 'db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $quiz_id = $_GET['id'];

    // First, get the file path
    $sql = "SELECT file_path FROM quizzes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $quiz = $result->fetch_assoc();

    if ($quiz) {
        // Delete the file if it exists
        if (!empty($quiz['file_path']) && file_exists($quiz['file_path'])) {
            unlink($quiz['file_path']);
        }

        // Now delete the quiz from the database
        $sql = "DELETE FROM quizzes WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $quiz_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => $stmt->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Quiz not found.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$conn->close();
?>