<?php
// Start session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Destroy session and redirect to login page
session_destroy();

// Redirect to login page (or homepage)
header('Location: index.php'); 
exit();
?>
