<?php
// get_subjects.php
include('../includes/db_connect.php');
include('../includes/function.php');
secure();
check_role('Staff');

header('Content-Type: application/json');

$user_id = $_SESSION['id'];

// Get staff's section ID
$stmt = $conn->prepare('SELECT s.section_id FROM staff s WHERE s.user_id = ?');
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_assignment = $result->fetch_assoc();
    $section_id = $user_assignment['section_id'];
    
    // Fetch subjects for the section
    $stmt = $conn->prepare('
        SELECT subjects.* 
        FROM subjects
        INNER JOIN subject_section ON subjects.id = subject_section.subject_id
        WHERE subject_section.section_id = ?
    ');
    $stmt->bind_param("i", $section_id);
    $stmt->execute();
    $subjects = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    echo json_encode($subjects);
} else {
    echo json_encode(['error' => 'You are not assigned to any section.']);
}

$stmt->close();
$conn->close();
?>