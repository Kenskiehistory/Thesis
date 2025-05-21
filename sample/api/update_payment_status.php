<?php
include('../includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $waitlist_id = $_POST['waitlist_id'];
    $current_status = $_POST['current_status'];
    $user_profile_id = $_POST['user_profile_id'];

    if ($current_status == 'Pending') {
        $stmt = $conn->prepare("UPDATE waitlist SET payment_status = 'Paid' WHERE waitlist_id = ?");
        $stmt->bind_param("i", $waitlist_id);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to update payment status']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid current status']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>