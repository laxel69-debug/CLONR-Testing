<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once '../config.php';

try {
    // Verify admin
    $admin_id = $_SESSION['admin_id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ? AND user_type = 'admin'");
    $stmt->execute([$admin_id]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        session_unset();
        session_destroy();
        header('Location: ../login.php');
        exit;
    }

    // Get order ID from URL
    $order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if ($order_id <= 0) {
        header('Location: order.php');
        exit;
    }

    // Fetch order details
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        header('Location: order.php');
        exit;
    }

    // Fetch order items
    $stmt = $conn->prepare("SELECT oi.*, p.name as product_name, p.image 
                          FROM order_items oi 
                          JOIN products p ON oi.product_id = p.id 
                          WHERE oi.order_id = ?");
    $stmt->execute([$order_id]);
    $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLONR - Order Details</title>
    <link rel="stylesheet" href="../global.css">
    <link rel="stylesheet" href="admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <a href="admindashboard.php"><h1 class="title">CLONR</h1></a>
            <nav>
                <ul class="navbar">
                    <li><a href="products.php">Products</a></li>
                    <li><a href="order.php">Orders</a></li>
                    <li><a href="users.php">Users</a></li>
                    
                    <li><a href="messages.php">Messages</a></li>
                    <li><a href="../AdminUpdateProfile.php">Profile</a></li>
                    <li><a href="../logout.php" class="logout-btn">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="admin-main">
        <div class="admin-container">
            <div class="back-button">
                <a href="order.php" class="btn"><i class="fas fa-arrow-left"></i> Back to Orders</a>
            </div>
            
            <h1 class="admin-title">Order Details #<?= $order['id'] ?></h1>
            
            <div class="order-details-container">
                <div class="order-summary">
                    <div class="summary-card">
                        <h3>Order Information</h3>
                        <div class="info-row">
                            <span>Order ID:</span>
                            <span>#<?= $order['id'] ?></span>
                        </div>
                        <div class="info-row">
                            <span>Date:</span>
                            <span><?= date('M d, Y h:i A', strtotime($order['placed_on'])) ?></span>
                        </div>
                        <div class="info-row">
                            <span>Status:</span>
                            <span class="status-badge status-<?= $order['payment_status'] ?>">
                                <?= ucfirst($order['payment_status']) ?>
                            </span>
                        </div>
                        <div class="info-row">
                            <span>Payment Method:</span>
                            <span><?= ucfirst($order['method']) ?></span>
                        </div>
                        <div class="info-row">
                            <span>Total Amount:</span>
                            <span>₱<?= number_format($order['total_price'], 2) ?></span>
                        </div>
                    </div>
                    
                    <div class="summary-card">
                        <h3>Customer Information</h3>
                        <div class="info-row">
                            <span>Name:</span>
                            <span><?= $order['name'] ?></span>
                        </div>
                        <div class="info-row">
                            <span>Email:</span>
                            <span><?= $order['email'] ?></span>
                        </div>
                        <div class="info-row">
                            <span>Phone:</span>
                            <span><?= $order['number'] ?></span>
                        </div>
                    </div>
                    
                    <div class="summary-card">
                        <h3>Shipping Address</h3>
                        <p><?= nl2br($order['address']) ?></p>
                    </div>
                </div>
                
                <div class="order-items">
                    <h3>Order Items</h3>
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order_items as $item): ?>
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <?php if (!empty($item['image'])): ?>
                                        <img src="../uploaded_img/<?= $item['image'] ?>" alt="<?= $item['product_name'] ?>" width="50">
                                        <?php endif; ?>
                                        <span><?= $item['product_name'] ?></span>
                                    </div>
                                </td>
                                <td>₱<?= number_format($item['price'], 2) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td>₱<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                <td><strong>₱<?= number_format($order['total_price'], 2) ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="order-actions">
                    <form action="update_order_status.php" method="post">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <div class="form-group">
                            <label for="status">Update Status:</label>
                            <select name="status" id="status" required>
                                <option value="pending" <?= $order['payment_status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="completed" <?= $order['payment_status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="cancelled" <?= $order['payment_status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="notes">Admin Notes (Optional):</label>
                            <textarea name="notes" id="notes" rows="3" placeholder="Add any notes about this order update"></textarea>
                        </div>
                        <button type="submit" class="btn update-btn">Update Order Status</button>
                    </form>
                </div>
                <div class="order-history">
    <h3>Order History</h3>
    <?php
    $history_stmt = $conn->prepare("SELECT oh.*, u.name as admin_name 
                                  FROM order_history oh
                                  JOIN users u ON oh.updated_by = u.id
                                  WHERE oh.order_id = ?
                                  ORDER BY oh.updated_at DESC");
    $history_stmt->execute([$order_id]);
    $history = $history_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($history)): ?>
        <table class="history-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Updated By</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($history as $record): ?>
                <tr>
                    <td><?= date('M d, Y h:i A', strtotime($record['updated_at'])) ?></td>
                    <td><span class="status-badge status-<?= $record['status'] ?>"><?= ucfirst($record['status']) ?></span></td>
                    <td><?= $record['admin_name'] ?></td>
                    <td><?= nl2br(htmlspecialchars($record['notes'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No history records found for this order.</p>
    <?php endif; ?>
</div>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-content">
            <h2>CLONR - Wear the Movement</h2>
            <p>Email: noreply.CLONR@gmail.com | Phone: +63 XXX XXX XXXX</p>
            <p>© 2025 CLONR. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>