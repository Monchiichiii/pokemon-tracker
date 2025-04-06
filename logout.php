<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear all session data and destroy session
$_SESSION = [];
session_unset();
session_destroy();

// Redirect to homepage
header("Location: index.php");
exit;
