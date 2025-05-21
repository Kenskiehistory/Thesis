<?php
// Include your database connection file
include('includes/db_connect.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the selected course review from the AJAX request
$courseReview = $_POST['courseReview'];

// Prepare and execute the query to fetch sections based on the selected course review
$query = $conn->prepare("SELECT section_name, section_limit, section_count FROM sectioning WHERE course_id = ?");
$query->bind_param('s', $courseReview);
$query->execute();
$result = $query->get_result();

// Generate options for the section select element
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $section_name = $row['section_name'];
        $section_limit = $row['section_limit'];
        $section_count = $row['section_count'];
        $available_slots = $section_limit - $section_count;
        
        if ($available_slots > 0) {
            echo "<option value='" . $section_name . "'>" . $section_name . " (Limit: " . $section_limit . ", Available: " . $available_slots . ")</option>";
        } else {
            echo "<option value='" . $section_name . "' disabled>" . $section_name . " (Limit: " . $section_limit . ", Available: 0) - FULL</option>";
        }
    }
} else {
    echo "<option value=''>No sections available</option>";
}

// Close the database connection
$conn->close();
?>
