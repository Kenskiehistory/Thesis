<?php
// delete_content.php
include('../includes/db_connect.php');
include('../includes/function.php');
secure();
check_role('Staff');

$response = ['success' => false, 'message' => '', 'subject_id' => null];

if (isset($_GET['id'])) {
    $content_id = $_GET['id'];

    // Fetch the subject_id before deleting the content
    $stmt = $conn->prepare('SELECT subject_id FROM subject_contents WHERE id = ?');
    $stmt->bind_param("i", $content_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $content = $result->fetch_assoc();
    $subject_id = $content['subject_id'];
    $stmt->close();

    // Delete the content
    $stmt = $conn->prepare('DELETE FROM subject_contents WHERE id = ?');
    $stmt->bind_param("i", $content_id);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Content deleted successfully';
        $response['subject_id'] = $subject_id;
    } else {
        $response['message'] = 'Error deleting content';
    }
    $stmt->close();
} else {
    $response['message'] = 'Content ID not provided';
}

echo json_encode($response);