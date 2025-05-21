<?php
session_start();

header('Content-Type: application/json');

// Ensure the session is active and contains data
if (isset($_SESSION['id']) && isset($_SESSION['email'])) {
    // Respond with user session details
    echo json_encode([
        'id' => $_SESSION['id'],
        'email' => $_SESSION['email'],
        'username' => $_SESSION['username'],
        'roles' => $_SESSION['roles'],
        'course' => $_SESSION['course'] ?? null,
        'section_id' => $_SESSION['section_id'] ?? null,
    ]);
} else {
    // Respond with an error if no session exists
    echo json_encode(['error' => 'No active session']);
}
?>
