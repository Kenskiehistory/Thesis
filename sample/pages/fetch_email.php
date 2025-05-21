<?php
include('../includes/db_connect.php');

$role = $_GET['role'] ?? '';
$profile_id = $_GET['profile_id'] ?? '';

if ($role == 'Student') {
    $query = "SELECT email_stud AS email FROM user_profile WHERE id = ?";
} elseif ($role == 'Staff') {
    $query = "SELECT email FROM staff WHERE id = ?";
} else {
    echo '';
    exit;
}

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $profile_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo $row['email'] ?? '';