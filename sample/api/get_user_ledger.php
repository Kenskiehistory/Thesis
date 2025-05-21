<?php
require_once('../includes/db_connect.php');
// 3. get_user_ledger.php
if (basename($_SERVER['PHP_SELF']) == 'get_user_ledger.php') {
    $user_profile_id = isset($_GET['user_profile_id']) ? intval($_GET['user_profile_id']) : 0;
    
    $ledger_entries = [];
    $stmt = $conn->prepare('SELECT l.date, l.particulars, l.debit, l.credit, l.balance FROM user_ledger l WHERE l.user_profile_id = ? ORDER BY l.date ASC');
    $stmt->bind_param("i", $user_profile_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $ledger_entries[] = $row;
    }
    
    echo json_encode($ledger_entries);
}
?>