<?php
include('../includes/db_connect.php');

$user_profile_id = isset($_GET['user_profile_id']) ? $_GET['user_profile_id'] : null;

if ($user_profile_id) {
    $stmt = $conn->prepare("SELECT waitlist_id, payment_status FROM waitlist WHERE user_profile_id = ?");
    $stmt->bind_param("i", $user_profile_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $waitlist_info = $result->fetch_assoc();
    $stmt->close();

    header('Content-Type: application/json');
    echo json_encode($waitlist_info);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'User profile ID is required']);
}
?>