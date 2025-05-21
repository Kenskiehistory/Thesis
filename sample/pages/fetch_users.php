<?php
include('../includes/db_connect.php');
include('../includes/config.php');

$id = $_GET['id'];

if ($stmt = $conn->prepare('SELECT * FROM users_new WHERE id = ?')) {
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    echo json_encode($user);
} else {
    echo json_encode(['error' => 'Failed to fetch user data']);
}