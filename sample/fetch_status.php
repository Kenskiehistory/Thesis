<?php
// Include your database connection file
include('includes/db_connect.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the selected course name from the AJAX request
$courseName = $_POST['courseStatus'];

// Prepare and execute the query to fetch sections based on the selected course
$query = $conn->prepare("SELECT coursename, c_status FROM tb_status WHERE coursename = ?");
$query->bind_param('s', $courseName);
$query->execute();
$result = $query->get_result();

// Generate options for the section select element
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['c_status'] . "'>" . $row['c_status'] . "</option>";
    }
} else {
    echo "<option value=''>No status available</option>";
}

// Close the database connection
$conn->close();
?>
