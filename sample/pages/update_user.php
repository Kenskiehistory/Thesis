<?php
include('../includes/db_connect.php');
include('../includes/config.php');

$id = $_POST['id'];
$username = $_POST['username'];
$email = $_POST['email'];
$roles = $_POST['roles'];
$active = $_POST['active'];

if ($stmt = $conn->prepare('UPDATE users_new SET username = ?, email = ?, roles = ?, active = ? WHERE id = ?')) {
    $stmt->bind_param('sssii', $username, $email, $roles, $active, $id);
    $result = $stmt->execute();
    $stmt->close();

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update user']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
}