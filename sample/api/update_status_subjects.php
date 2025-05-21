<?php
include('../includes/db_connect.php');
include('../includes/function.php');
secure();
check_role('Staff');  // Ensure only staff can update subject status

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subjectId = isset($_POST['subjectId']) ? intval($_POST['subjectId']) : 0;
    $newStatus = isset($_POST['newStatus']) ? intval($_POST['newStatus']) : 0;

    if ($subjectId > 0) {
        $stmt = $conn->prepare("UPDATE subjects SET is_active = ? WHERE id = ?");
        $stmt->bind_param('ii', $newStatus, $subjectId);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Subject status updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update subject status: ' . $conn->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid subject ID.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$conn->close();
?>