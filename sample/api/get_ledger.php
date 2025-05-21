<?php
include('../includes/db_connect.php');

header('Content-Type: application/json');

$user_profile_id = isset($_GET['user_profile_id']) ? $_GET['user_profile_id'] : null;

if ($user_profile_id) {
    $stmt = $conn->prepare('SELECT l.date, l.particulars, l.original_receipt, l.debit, l.credit, l.balance FROM user_ledger l WHERE l.user_profile_id = ? ORDER BY l.date ASC');
    $stmt->bind_param("i", $user_profile_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $ledger_entries = [];
    while ($row = $result->fetch_assoc()) {
        $ledger_entries[] = $row;
    }

    echo json_encode(['success' => true, 'data' => $ledger_entries]);
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'User profile ID is required']);
}
?>
