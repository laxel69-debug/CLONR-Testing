<?php
session_start();
require_once '../config.php'; // Your database configuration

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $subject = $_POST['subject'];
    $messageContent = $_POST['message'];
    
    // Get admin details from session or database
    $adminName = $_SESSION['admin_name'] ?? 'CLONR Admin';
    $adminEmail = $_SESSION['admin_email'] ?? 'noreply.CLONR@gmail.com';
    
    try {
        $stmt = $conn->prepare("INSERT INTO messages 
                              (user_id, name, email, message, subject, sender_type, created_at, is_read) 
                              VALUES (?, ?, ?, ?, ?, 'admin', NOW(), 0)");
        $stmt->bind_param("issss", $userId, $adminName, $adminEmail, $messageContent, $subject);
        $stmt->execute();
        
        // Redirect back with success message
        $_SESSION['message_success'] = "Message sent successfully!";
        header('Location: messages.php');
        exit;
    } catch (Exception $e) {
        // Handle error
        $_SESSION['message_error'] = "Error sending message: " . $e->getMessage();
        header('Location: messages.php');
        exit;
    }
} else {
    header('Location: messages.php');
    exit;
}
?>