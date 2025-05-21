<?php
// db_connection.php
include('../includes/db_connect.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['id']) && isset($_POST['review_status'])) {
    $id = $_POST['id'];
    $review_status = $_POST['review_status'];

    $sql = "UPDATE course_reviews SET review_status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $review_status, $id);

    if ($stmt->execute()) {
        echo "Review status updated successfully!";
    } else {
        echo "Error updating review status: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request!";
}

$conn->close();
?>
