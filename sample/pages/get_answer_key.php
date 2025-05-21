<?php
include('../includes/db_connect.php');
include('../includes/function.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT answer_key FROM exam_answer_keys WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode(['success' => true, 'answer_key' => $row['answer_key']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Answer key not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing exam ID']);
}
?>