<?php
ob_start(); // Start output buffering

// Enable error reporting for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection file
include 'includes/db_connect.php';

// Set the response content type to JSON
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $title = $_POST['title'] ?? '';
    $course_name = $_POST['course_name'] ?? '';
    $num_questions = $_POST['num_questions'] ?? '';
    $answers = $_POST['answers'] ?? [];

    // Handle the file upload
    $targetDir = "../quizzes/uploads/";
    $fileName = basename($_FILES['file']['name']);
    $targetFilePath = $targetDir . $fileName;

    if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)) {
            // Prepare the SQL statement
            $stmt = $conn->prepare("INSERT INTO quizzes (title, course_name, created_by, created_at, file_path, num_questions, answers) VALUES (?, ?, ?, NOW(), ?, ?, ?)");
            $created_by = 'admin';
            $answers_json = json_encode($answers);

            // Bind the parameters and execute the statement
            $stmt->bind_param("sssss", $title, $course_name, $created_by, $targetFilePath, $num_questions, $answers_json);
            if ($stmt->execute()) {
                // Return a success response
                http_response_code(200);
                echo json_encode(['success' => true]);
            } else {
                // Return a database error response
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
            }

            // Close the statement
            $stmt->close();
        } else {
            // Return a file upload error response
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'File upload error.']);
        }
    } else {
        // Return a file upload error response
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'File upload error.']);
    }

    // Close the database connection
    $conn->close();
} else {
    // Return an error response for invalid request
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

// Clear and ensure no unexpected output
$output = ob_get_clean();
if (!empty($output)) {
    echo json_encode(['success' => false, 'message' => 'Unexpected output: ' . htmlentities($output)]);
    exit;
}
?>
