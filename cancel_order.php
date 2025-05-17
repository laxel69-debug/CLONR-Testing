<?php
@include 'config.php';
session_start();

if(!isset($_SESSION['user_id'])){
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$order_id = isset($data['order_id']) ? intval($data['order_id']) : 0;

if($order_id <= 0){
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid order ID']);
    exit;
}

try {
    // Verify the order belongs to the user
    $check_order = $conn->prepare("SELECT user_id, total_price FROM orders WHERE id = ? AND payment_status = 'pending'");
    $check_order->execute([$order_id]);
    $order = $check_order->fetch(PDO::FETCH_ASSOC);

    if(!$order){
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Order not found or not pending']);
        exit;
    }

    // Start transaction
    $conn->beginTransaction();

    // Update order status
    $update_order = $conn->prepare("UPDATE orders SET payment_status = 'canceled' WHERE id = ?");
    $update_order->execute([$order_id]);

    // Refund money to user
    $refund_user = $conn->prepare("UPDATE users SET money = money + ? WHERE id = ?");
    $refund_user->execute([$order['total_price'], $_SESSION['user_id']]);

    // Commit transaction
    $conn->commit();

    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    
} catch (PDOException $e) {
    $conn->rollBack();
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>