<?php
session_start();
include('../includes/db_connect.php');
include('../includes/function.php');
secure(); // Ensure the user is logged in

if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$userId = $_SESSION['id'];

// Fetch the student profile to get the user_profile_id
$stmt = $conn->prepare("SELECT id FROM user_profile WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    echo json_encode(['error' => 'Student not found']);
    exit;
}

// Fetch ledger entries for the student
$stmt = $conn->prepare('SELECT * FROM user_ledger WHERE user_profile_id = ? ORDER BY date ASC');
$stmt->bind_param('i', $student['id']);
$stmt->execute();
$ledgerResult = $stmt->get_result();

$ledgerEntries = [];
while ($ledger = $ledgerResult->fetch_assoc()) {
    $ledgerEntries[] = $ledger;
}

$stmt->close();

// Return data as JSON
echo json_encode($ledgerEntries);
?>