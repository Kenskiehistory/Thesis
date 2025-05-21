<?php
include('../includes/db_connect.php');
include('../includes/function.php');

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON input');
    }

    $start_date = $data['student_start_date'];
    $end_date = $data['student_end_date'];
    $course = $data['course'];

    $query = "SELECT up.fName, up.lName, up.courseName, un.email, up.conNumb, un.added
              FROM user_profile up
              JOIN users_new un ON up.user_id = un.id
              WHERE un.added BETWEEN ? AND ?";
    $params = [$start_date, $end_date];

    if ($course !== 'all') {
        $query .= " AND up.courseName = ?";
        $params[] = $course;
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    $stmt->close();

    echo json_encode(['students' => $students]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}