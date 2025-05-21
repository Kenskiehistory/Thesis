<?php
include('../includes/db_connect.php');

$email = $_GET['email'];
$stmt = $conn->prepare("SELECT COUNT(*) FROM staff WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

header('Content-Type: application/json');
echo json_encode(['exists' => $count > 0]);
?>