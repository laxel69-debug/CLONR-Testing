<?php
// Start session
session_start();
echo '<pre>';
print_r($_SESSION);
echo '</pre>';


// Redirect if not logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

// Include the database configuration
require_once '../config.php';

try {
    // Get admin information
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

    // Function to count orders by payment status
    function countOrdersByStatus($conn, $status) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM orders WHERE payment_status = :status");
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Get counts for each status
    $pending_count = countOrdersByStatus($conn, 'pending');
    $completed_count = countOrdersByStatus($conn, 'completed');
    $cancelled_count = countOrdersByStatus($conn, 'cancelled');

    // Get recent orders (last 5)
    $stmt = $conn->prepare("SELECT * FROM orders ORDER BY placed_on DESC LIMIT 5");
    $stmt->execute();
    $recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate total sales
    $total_sales_stmt = $conn->query("SELECT SUM(total_price) FROM orders WHERE payment_status = 'completed'");
    $total_sales = $total_sales_stmt->fetchColumn();

    // Count total users (excluding admins)
    $total_users_stmt = $conn->query("SELECT COUNT(*) FROM users WHERE user_type = 'user'");
    $total_users = $total_users_stmt->fetchColumn();

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>CLONR</title>
        <link rel="stylesheet" href="../global.css"/>
        <link rel="stylesheet" href="admin.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
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
        </header>
        <main class="admin-main">
            <div class="admin-container">
                <h1 class="admin-title">Admin Dashboard</h1>
                <p class="admin-welcome">Welcome back, <?= htmlspecialchars($admin['name'] ?? 'Administrator') ?>!</p>
                
                <div class="stats-container">
                    <div class="stat-card pending">
                        <h3>Pending Orders</h3>
                        <p class="stat-number"><?= htmlspecialchars($pending_count) ?></p>
                        <a href="pending_orders.php?status=pending" class="stat-link">View Details</a>
                    </div>
                    
                    <div class="stat-card approved">
                        <h3>Completed Orders</h3>
                        <p class="stat-number"><?= htmlspecialchars($completed_count) ?></p>
                        <a href="completed_orders.php?status=completed" class="stat-link">View Details</a>
                    </div>
                    
                    <div class="stat-card declined">
                        <h3>Cancelled Orders</h3>
                        <p class="stat-number"><?= htmlspecialchars($cancelled_count) ?></p>
                        <a href="cancelled_orders.php?status=cancelled" class="stat-link">View Details</a>
                    </div>
                    
                    <div class="stat-card sales">
                        <h3>Total Sales</h3>
                        <p class="stat-number">₱<?= htmlspecialchars(number_format($total_sales, 2)) ?></p>
                        <a href="sales_report.php" class="stat-link">View Report</a>
                    </div>
                    
                    <div class="stat-card users">
                        <h3>Total Users</h3>
                        <p class="stat-number"><?= htmlspecialchars($total_users) ?></p>
                        <a href="users.php" class="stat-link">Manage Users</a>
                    </div>
                </div>
                
                <div class="recent-orders">
                    <h2>Recent Orders</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Products</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_orders as $order): ?>
                            <tr>
                                <td>#<?= htmlspecialchars($order['id']) ?></td>
                                <td><?= htmlspecialchars($order['name']) ?></td>
                                <td><?= htmlspecialchars($order['total_products']) ?></td>
                                <td>$<?= htmlspecialchars(number_format($order['total_price'], 2)) ?></td>
                                <td><?= htmlspecialchars(date('M d, Y', strtotime($order['placed_on']))) ?></td>
                                <td><span class="status <?= htmlspecialchars($order['payment_status']) ?>"><?= ucfirst(htmlspecialchars($order['payment_status'])) ?></span></td>
                                <td><a href="order_details.php?id=<?= $order['id'] ?>" class="action-btn">View</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
        <hr class="custom-hr">

        <footer>
        <div class="footer-content">
            <h2>CLONR - Wear the Movement</h2>
            <p>Email: noreply.CLONR@gmail.com | Phone: +63 XXX XXX XXXX</p>
            <p>© 2025 CLONR. All Rights Reserved.</p>
        </div>
        </footer>
    </body>
</html>