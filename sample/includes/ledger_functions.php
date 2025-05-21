<?php
include('db_connect.php');

function add_debit($user_profile_id, $amount, $particulars) {
    global $conn;
    $date = date('Y-m-d');
    
    // Fetch the latest balance
    $stmt = $conn->prepare('SELECT balance FROM user_ledger WHERE user_profile_id = ? ORDER BY date DESC, id DESC LIMIT 1');
    $stmt->bind_param("i", $user_profile_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $latest_entry = $result->fetch_assoc();
    $stmt->close();

    $balance = $latest_entry ? $latest_entry['balance'] + $amount : $amount;

    // Insert the debit entry
    $stmt = $conn->prepare('INSERT INTO user_ledger (user_profile_id, date, particulars, debit, balance) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param("isssd", $user_profile_id, $date, $particulars, $amount, $balance);
    $stmt->execute();
    $stmt->close();
}

function add_credit($user_profile_id, $amount, $particulars, $original_receipt) {
    global $conn;
    $date = date('Y-m-d');
    
    // Fetch the latest balance
    $stmt = $conn->prepare('SELECT balance FROM user_ledger WHERE user_profile_id = ? ORDER BY date DESC, id DESC LIMIT 1');
    $stmt->bind_param("i", $user_profile_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $latest_entry = $result->fetch_assoc();
    $stmt->close();

    $balance = $latest_entry ? $latest_entry['balance'] - $amount : -$amount;

    // Insert the credit entry with the OR
    $stmt = $conn->prepare('INSERT INTO user_ledger (user_profile_id, date, particulars, credit, balance, original_receipt) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->bind_param("issdds", $user_profile_id, $date, $particulars, $amount, $balance, $original_receipt);
    $stmt->execute();
    $stmt->close();
}

//paymongo
function add_credit_paymongo($user_profile_id, $amount, $particulars, $original_receipt) {
    global $conn;
    $date = date('Y-m-d');
    
    // Fetch the latest balance
    $stmt = $conn->prepare('SELECT balance FROM user_ledger WHERE user_profile_id = ? ORDER BY date DESC, id DESC LIMIT 1');
    $stmt->bind_param("i", $user_profile_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $latest_entry = $result->fetch_assoc();
    $stmt->close();

    $balance = $latest_entry ? $latest_entry['balance'] - $amount : -$amount;

    // Insert the credit entry
    $stmt = $conn->prepare('INSERT INTO user_ledger (user_profile_id, date, particulars, credit, balance, original_receipt) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->bind_param("issdds", $user_profile_id, $date, $particulars, $amount, $balance, $original_receipt);
    $stmt->execute();
    $stmt->close();
}
?>