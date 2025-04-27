<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set JSON header
header('Content-Type: application/json');

// Verify the script is being called
file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] Script accessed\n", FILE_APPEND);

// Database configuration
$host = 'localhost';
$dbname = 'clonr_db';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

session_start();

// Check if user_id is set in the session (assuming they are logged in)
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id']) || $_SESSION['user_id'] <= 0) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    // Retrieve product details from the POST request
    $product_id = $_POST['pid'];
    $product_name = $_POST['name'];
    $product_price = $_POST['price'];
    $product_image = $_POST['image'];
    $size = $_POST['size'];
    $quantity = (int)$_POST['quantity'];

    // Basic validation (you might want to add more)
    if (empty($product_id) || empty($product_name) || !is_numeric($product_price) || $product_price <= 0 || empty($size) || !is_numeric($quantity) || $quantity <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product details.']);
        exit;
    }

    try {
        $stmt = $conn->prepare("INSERT INTO cart (user_id, pid, name, size, quantity, price, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $product_id, $product_name, $size, $quantity, $product_price, $product_image]);

        echo json_encode(['success' => true, 'message' => 'Product added to cart!']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
?>
