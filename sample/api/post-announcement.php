<?php
// post-announcement.php

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Allow CORS (adjust in production)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include necessary files
require_once '../includes/config.php';
require_once '../includes/db_connect.php';
require_once '../includes/function.php';

// Start session
session_start();

// Check if the user is logged in and has the staff role
if (!isset($_SESSION['id']) || !check_role('Staff')) {
    http_response_code(403);
    echo json_encode(array("message" => "Access denied. Only staff members can post announcements."));
    exit();
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed. Use POST."));
    exit();
}

// Get POST data
$data = json_decode(file_get_contents("php://input"));

// Validate input
if (!isset($data->title) || !isset($data->content) || empty(trim($data->title)) || empty(trim($data->content))) {
    http_response_code(400);
    echo json_encode(array("message" => "Missing required fields."));
    exit();
}

// Sanitize input
$title = htmlspecialchars(strip_tags($data->title));
$content = htmlspecialchars(strip_tags($data->content));
$created_by = $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];

// Get the staff member's course
$user_id = $_SESSION['id'];
$stmt = $conn->prepare('SELECT courseName FROM staff WHERE user_id = ?');
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    http_response_code(400);
    echo json_encode(array("message" => "You are not assigned to any course."));
    exit();
}

$user_assignment = $result->fetch_assoc();
$courseName = $user_assignment['courseName'];

$stmt->close();

// Insert announcement into database
$stmt = $conn->prepare('INSERT INTO announcements (courseName, title, content, created_by, created_at) VALUES (?, ?, ?, ?, NOW())');
$stmt->bind_param("ssss", $courseName, $title, $content, $created_by);

if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode(array("message" => "Announcement created successfully."));
} else {
    http_response_code(500);
    echo json_encode(array("message" => "Unable to create announcement."));
}

$stmt->close();
$conn->close();
?>