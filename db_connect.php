<?php
// db_connect.php
$host = 'localhost';
$dbname = 'clonr_db';
$username = 'root';
$password = '';

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $conn = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    error_log("Database Connection Error: " . $e->getMessage());
    // In a real application, you might redirect to an error page
    // For now, we'll let the calling script handle the display error
    // die("Database connection failed."); // Avoid die() here so the including script can handle it
    $conn = null; // Ensure $conn is null if connection fails
}
?>