<?php
include('../includes/db_connect.php');


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// insert_course_review.php
if (isset($_POST['coursename']) && isset($_POST['reviews']) && isset($_POST['tuition_fee'])) {
    // Retrieve form data
    $coursename = $_POST['coursename'];
    $reviews = $_POST['reviews'];
    $tuition_fee = $_POST['tuition_fee'];
    

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO course_reviews (coursename, reviews, tuition_fee, review_status) VALUES (?, ?, ?,'Active')");
    $stmt->bind_param("sss", $coursename, $reviews, $tuition_fee);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Course review added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request!";
}
?>
