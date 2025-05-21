<?php
include 'includes/db_connect.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set the response content type to JSON
header('Content-Type: application/json');

try {
    // Include the database connection file

    // Fetch quizzes from the database
    $sql = "SELECT * FROM quizzes ORDER BY created_at DESC";
    $result = $conn->query($sql);

    if ($result === false) {
        throw new Exception("Query failed: " . $conn->error);
    }

    $quizzes = array();
    while($row = $result->fetch_assoc()) {
        $quizzes[] = $row;
    }

    echo json_encode($quizzes);

    if ($result->num_rows === 0) {
        echo json_encode([]);
    } else {
        $quizzes = array();
        while($row = $result->fetch_assoc()) {
            $quizzes[] = $row;
        }
        echo json_encode($quizzes);
    }
    

} catch (Exception $e) {
    // If an error occurs, return a JSON-encoded error message
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

// Close the connection if it exists
if (isset($conn)) {
    $conn->close();
}

file_put_contents('error_log.txt', $e->getMessage(), FILE_APPEND);

?>