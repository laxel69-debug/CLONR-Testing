<?php
// Start session and check if admin is logged in
session_start();
if (!isset($_SESSION['admin_id'])) {
    $_SESSION['error'] = "Please login first";
    header("Location: login.php");
    exit();
}

$db_name = "mysql:host=localhost;dbname=clonr_db";
$username = "root";
$password = "";

try {
    // Connect to database
    $conn = new PDO($db_name, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get form data
    $user_id = $_POST['user_id'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    $admin_id = $_SESSION['admin_id'];

    // Validate inputs
    if (empty($user_id) || empty($subject) || empty($message)) {
        $_SESSION['error'] = "Please fill all fields";
        header("Location: messages.php");
        exit();
    }

    // Insert message
    $stmt = $conn->prepare("
        INSERT INTO messages
        (user_id, sender_id, sender_type, subject, message, is_read, created_at)
        VALUES 
        (:user_id, :sender_id, 'admin', :subject, :message, 0, NOW())
    ");
    
    $stmt->execute([
        ':user_id' => $user_id,
        ':sender_id' => $admin_id,
        ':subject' => $subject,
        ':message' => $message
    ]);
    
    $_SESSION['success'] = "Message sent successfully!";
    header("Location: messages.php");
    exit();

} catch (PDOException $e) {
    $_SESSION['error'] = "Error sending message: " . $e->getMessage();
    header("Location: messages.php");
    exit();
}