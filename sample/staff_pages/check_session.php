<?php
session_start();
include('../includes/config.php');
include('../includes/function.php');

header('Content-Type: application/json');

$loggedIn = isset($_SESSION['id']);
$role = $loggedIn ? $_SESSION['roles'] : null; // Note the use of 'roles' instead of 'role'

echo json_encode([
    'loggedIn' => $loggedIn,
    'role' => $role
]);
?>