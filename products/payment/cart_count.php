<?php
session_start();
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id']) || $_SESSION['user_id'] <= 0) {
    echo '0'; // User not logged in, cart count is 0
    exit;
}

$user_id = $_SESSION['user_id'];

$host = 'localhost';
$dbname = 'clonr_db';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $cartCount = $stmt->fetchColumn();

    echo $cartCount;

} catch(PDOException $e) {
    echo '0'; // Default to 0 if there's an error
}
?>