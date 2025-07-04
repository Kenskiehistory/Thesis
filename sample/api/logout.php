<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Clear any specific cookies if needed
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Optionally, add CORS headers if needed
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirect to login page
header("Location: ../index.php");
exit();
?>