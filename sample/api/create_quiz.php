<?php
require_once 'db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $course_name = $_POST['course_name'];
    $created_by = $_SESSION['user_id']; // Assuming you have a user session
    $answers = $_POST['answers']; // This is already a JSON string
    $num_questions = count(json_decode($answers, true));

    // Handle file upload
    $file_path = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/quizzes/';
        $file_name = uniqid() . '_' . basename($_FILES['file']['name']);
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
            // File uploaded successfully
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload file.']);
            exit;
        }
    }

    $sql = "INSERT INTO quizzes (title, course_name, created_by, created_at, file_path, answers, num_questions) 
            VALUES (?, ?, ?, NOW(), ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssissi", $title, $course_name, $created_by, $file_path, $answers, $num_questions);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$conn->close();
?>