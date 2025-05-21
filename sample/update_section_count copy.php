<?php
// Include your database connection file
include('includes/db_connect.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the selected section ID and student ID from the AJAX request
$sectionId = $_POST['sectionId'];
$studentId = $_POST['studentId'];

// Check if the student is already enrolled in the section
$query = $conn->prepare("SELECT * FROM sectioning_student WHERE student_id = ? AND section_id = ?");
$query->bind_param('ii', $studentId, $sectionId);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    echo "Student already enrolled in this section.";
} else {
    // Begin transaction
    $conn->begin_transaction();
    
    try {
        // Insert the student into the sectioning_student table
        $query = $conn->prepare("INSERT INTO sectioning_student (student_id, section_id) VALUES (?, ?)");
        $query->bind_param('ii', $studentId, $sectionId);
        $query->execute();

        // Increment the section count in the sectioning table
        $query = $conn->prepare("UPDATE sectioning SET section_count = section_count + 1 WHERE id = ?");
        $query->bind_param('i', $sectionId);
        $query->execute();

        // Commit transaction
        $conn->commit();
        
        echo "Student successfully enrolled and section count updated.";
    } catch (Exception $e) {
        // Rollback transaction if something goes wrong
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}

// Close the database connection
$conn->close();
?>
