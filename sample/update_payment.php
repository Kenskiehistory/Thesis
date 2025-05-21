<?php
include('includes/db_connect.php');
include('includes/function.php');

secure();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $waitlist_id = $_POST['waitlist_id'];
    $current_status = $_POST['current_status'];

    if ($current_status == 'Pending') {
        $query = "UPDATE waitlist SET payment_status = 'Paid' WHERE waitlist_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $waitlist_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Payment already processed']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}