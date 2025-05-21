<?php
include('../includes/db_connect.php');
include('../includes/function.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $conn->prepare('INSERT INTO staff (firstName, middleName, lastName, contactInfo, email, courseName, active) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param("ssssssi", $_POST['firstName'], $_POST['middleName'], $_POST['lastName'], $_POST['contactInfo'], $_POST['email'], $_POST['courseName'], $_POST['active']);
        $stmt->execute();

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Staff member added successfully']);
    } catch (mysqli_sql_exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>