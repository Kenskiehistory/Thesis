<?php
// Include your database connection file
include('includes/db_connect.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the selected review ID from the AJAX request
$reviewId = $_POST['reviewId'];

// Prepare and execute the query to fetch review details based on the selected review ID
$query = $conn->prepare("SELECT reviews, tuition_fee FROM course_reviews WHERE id = ?");
$query->bind_param('i', $reviewId);
$query->execute();
$result = $query->get_result();

// Fetch review details
$reviewDetails = $result->fetch_assoc();

// Close the database connection
$conn->close();

// Return the review details as JSON
echo json_encode($reviewDetails);
?>
