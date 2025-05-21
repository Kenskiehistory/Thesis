<?php
// Include your database connection file
include('includes/db_connect.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the selected course name from the AJAX request
$courseName = $_POST['courseName'];

// Prepare and execute the query to fetch reviews based on the selected course
$query = $conn->prepare("SELECT id, reviews, tuition_fee FROM course_reviews WHERE coursename = ? AND review_status = 'Active'");
$query->bind_param('s', $courseName);
$query->execute();
$result = $query->get_result();

// Generate options for the course reviews select element
$options = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $options .= "<option value='" . $row['id'] . "'>" . $row['reviews'] . " TOTAL FEE PHP." . $row['tuition_fee'] . "</option>";
    }
} else {
    $options = "<option value=''>No reviews available</option>";
}

// Close the database connection
$conn->close();

// Return the options
echo $options;
?>
