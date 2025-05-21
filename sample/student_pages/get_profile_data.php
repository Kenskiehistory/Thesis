<?php
session_start();
include('../includes/db_connect.php');
include('../includes/function.php');
secure(); // Ensure the user is logged in

if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$userId = $_SESSION['id'];

// Fetch the student profile
$stmt = $conn->prepare("SELECT * FROM user_profile WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$studentResult = $stmt->get_result();
$student = $studentResult->fetch_assoc();

if (!$student) {
    echo json_encode(['error' => 'Student not found']);
    exit;
}

// Fetch course-specific information
$course = $student['courseName'];
$courseSpecificData = [];

switch ($course) {
    case 'Architecture':
        $stmt = $conn->prepare("SELECT * FROM arki_stud WHERE student_id = ?");
        break;
    case 'Civil Engineering':
        $stmt = $conn->prepare("SELECT * FROM civil_stud WHERE student_id = ?");
        break;
    case 'Mechanical Engineering':
        $stmt = $conn->prepare("SELECT * FROM mecha_stud WHERE student_id = ?");
        break;
    case 'Master Plumbing':
        $stmt = $conn->prepare("SELECT * FROM plumber_stud WHERE student_id = ?");
        break;
    default:
        echo json_encode(['error' => 'Invalid course']);
        exit;
}

$stmt->bind_param("i", $student['id']);
$stmt->execute();
$courseResult = $stmt->get_result();
$courseSpecificData = $courseResult->fetch_assoc();

$stmt->close();

// Combine all data
$allData = array_merge($student, $courseSpecificData);

// Return data as JSON
echo json_encode($allData);
?>