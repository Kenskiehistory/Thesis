<?php
// filter_dashboard.php

include('../includes/db_connect.php');
include('../includes/function.php');

header('Content-Type: application/json');

try {
    $input = json_decode(file_get_contents('php://input'), true);

    // Fetch the start_date, end_date, and course from the request body
    $start_date = isset($input['start_date']) ? $input['start_date'] : date('Y-m-01');
    $end_date = isset($input['end_date']) ? $input['end_date'] : date('Y-m-t');
    $selected_course = isset($input['course']) ? $input['course'] : 'all';

    // Fetch total credit collected within the date range and for the selected course
    $query = "SELECT SUM(ul.credit) as total_credit 
              FROM user_ledger ul
              JOIN user_profile up ON ul.user_profile_id = up.id
              WHERE ul.date BETWEEN ? AND ?";
    $params = [$start_date, $end_date];
    
    if ($selected_course !== 'all') {
        $query .= " AND up.courseName = ?";
        $params[] = $selected_course;
    }
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
    $credit_result = $stmt->get_result();
    $credit_row = $credit_result->fetch_assoc();
    $total_credit = $credit_row['total_credit'] ? $credit_row['total_credit'] : 0;
    $stmt->close();

    // Return the result as a JSON response
    echo json_encode([
        'start_date' => $start_date,
        'end_date' => $end_date,
        'course' => $selected_course,
        'total_credit' => $total_credit
    ]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>