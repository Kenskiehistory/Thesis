<?php
require_once '../includes/db_connect.php';
session_start(); // Start the session to access session variables

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $course_name = $_POST['course_name'];
    $num_questions = $_POST['num_questions'];

    // File upload handling
    $target_dir = "../quizzes/uploads/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is a valid file type
    $allowedTypes = ['pdf', 'doc', 'docx', 'txt'];
    if (!in_array($fileType, $allowedTypes)) {
        echo "Sorry, only PDF, DOC, DOCX & TXT files are allowed.";
        $uploadOk = 0;
    }

    // Check if uploadOk is set to 0 by an error
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            // Prepare an insert statement
            $stmt = $conn->prepare("INSERT INTO quizzes (title, course_name, created_by, created_at, file_path, num_questions, answers) VALUES (?, ?, ?, NOW(), ?, ?, ?)");
            
            // Check if roles is set in the session
            if (isset($_SESSION['roles'])) {
                $roles = $_SESSION['roles'];

                if ($roles == 'Staff') {
                    // Get the user_id from the session
                    $user_id = $_SESSION['id'];
                    
                    // Fetch staff information from the database
                    $staff_stmt = $conn->prepare("SELECT firstName, middleName, lastName FROM staff WHERE user_id = ?");
                    $staff_stmt->bind_param("i", $user_id);
                    $staff_stmt->execute();
                    $staff_result = $staff_stmt->get_result();
                    
                    if ($staff_row = $staff_result->fetch_assoc()) {
                        // Concatenate the full name
                        $created_by = trim($staff_row['firstName'] . ' ' . $staff_row['middleName'] . ' ' . $staff_row['lastName']);
                    } else {
                        $created_by = "Unknown Staff";
                    }
                    
                    $staff_stmt->close();
                } else {
                    $created_by = "Admin";
                }
            } else {
                $created_by = "Unknown User";
            }

            // Convert answers array to a comma-separated string
            $answers = implode(',', $_POST['answers']);

            // Bind parameters and execute the statement
            $stmt->bind_param("ssssis", $title, $course_name, $created_by, $target_file, $num_questions, $answers);
            
            if ($stmt->execute()) {
                echo "The file " . htmlspecialchars(basename($_FILES["file"]["name"])) . " has been uploaded and quiz created successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
    $conn->close();
}
?>