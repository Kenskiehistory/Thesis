<?php
include('../includes/db_connect.php');
// Fetch waitlist data
$query = "SELECT w.*, CONCAT(up.fName, ' ', up.lName) AS student_name, up.courseName, up.id AS user_profile_id 
          FROM waitlist w 
          JOIN user_profile up ON w.user_profile_id = up.id";
$result = $conn->query($query);
$data = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode($data);
?>