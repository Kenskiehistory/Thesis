<?php
include('../includes/db_connect.php');

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];

$response = [];
$stmt = $conn->prepare("DELETE FROM exam_answer_keys WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['error'] = $conn->error;
}

echo json_encode($response);
?>
