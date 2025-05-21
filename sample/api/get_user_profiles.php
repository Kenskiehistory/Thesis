<?php
include('../includes/db_connect.php');

$user_profiles = [];
if ($result = $conn->query('SELECT id, fName, mName, lName FROM user_profile')) {
    while ($row = $result->fetch_assoc()) {
        $user_profiles[] = $row;
    }
    $result->free();
}

header('Content-Type: application/json');
echo json_encode($user_profiles);
?>