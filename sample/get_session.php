<?php
// get_session.php
session_start();

header('Content-Type: application/json');

// Check if the session is set
if (isset($_SESSION['id']) && isset($_SESSION['email'])) {
    echo json_encode([
        'id' => $_SESSION['id'],
        'email' => $_SESSION['email'],
        'username' => $_SESSION['username'],
        'roles' => $_SESSION['roles'],
        'course' => $_SESSION['course'] ?? null,
        'section_id' => $_SESSION['section_id'] ?? null
    ]);
} else {
    // If the session is not set, return an error
    http_response_code(401);
    echo json_encode(['error' => 'No session found']);
}
// Check if the session variables are set
if (isset($_SESSION['user_profile_id']) && isset($_SESSION['waitlist_id'])) {
    $userProfileId = $_SESSION['user_profile_id'];
    $waitlistId = $_SESSION['waitlist_id'];

    // Use the session variables as needed
    echo "User Profile ID: " . $userProfileId . "<br>";
    echo "Waitlist ID: " . $waitlistId . "<br>";
} else {
    // If session variables are not set
    echo "No session data found!";
}
?>
