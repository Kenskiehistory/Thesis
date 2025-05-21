<?php
session_start();
require_once 'db_connect.php';

$user_id = $_SESSION['user_id']; // Assuming you store user_id in session
$user_type = $_SESSION['user_type']; // 'student' or 'staff'

$table = ($user_type == 'student') ? 'user_profile' : 'staff';
$course_column = ($user_type == 'student') ? 'courseName' : 'courseName';

$stmt = $conn->prepare("SELECT $course_column FROM $table WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['courseName' => $row[$course_column]]);
} else {
    echo json_encode(['error' => 'User not found']);
}

$stmt->close();
$conn->close();
?>