<?php
// Start the session
session_start();

// Check if the session variables are set
if (isset($_SESSION['registration_token']) && isset($_SESSION['user_profile_id'])) {
    // Retrieve the token and user ID from the session
    $token = $_SESSION['registration_token'];
    $userProfileId = $_SESSION['user_profile_id'];

    // Display the token and user ID
    echo "<h1>Registration Complete</h1>";
    echo "<p>Your registration token is: <strong>" . htmlspecialchars($token) . "</strong></p>";
    echo "<p>Your user profile ID is: <strong>" . htmlspecialchars($userProfileId) . "</strong></p>";

    // Optionally, you can unset the session variables if you no longer need them
    // unset($_SESSION['registration_token']);
    // unset($_SESSION['user_profile_id']);
} else {
    // Handle the case where session variables are not set
    echo "<p>Error: Registration token not found.</p>";
}
?>
