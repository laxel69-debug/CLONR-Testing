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

    // Initialize filter variables
    $status_filter = isset($_GET['status']) ? $_GET['status'] : '';
    $search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
    $sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'placed_on';
    $sort_order = isset($_GET['order']) ? $_GET['order'] : 'DESC';

    // Validate sort parameters
    $valid_sort_columns = ['id', 'name', 'placed_on', 'total_price', 'payment_status'];
    $sort_by = in_array($sort_by, $valid_sort_columns) ? $sort_by : 'placed_on';
    $sort_order = strtoupper($sort_order) === 'ASC' ? 'ASC' : 'DESC';

    // Build base query
    $query = "SELECT * FROM orders WHERE 1=1";
    $params = [];

    // Apply status filter
    if (!empty($status_filter) && in_array($status_filter, ['pending', 'completed', 'cancelled'])) {
        $query .= " AND payment_status = ?";
        $params[] = $status_filter;
    }

    // Apply search filter
    if (!empty($search_query)) {
        $query .= " AND (name LIKE ? OR id = ? OR email LIKE ?)";
        $search_param = "%$search_query%";
        $params[] = $search_param;
        
        // Check if search query is numeric (possibly an order ID)
        if (is_numeric($search_query)) {
            $params[] = $search_query;
        } else {
            $params[] = $search_param;
        }
        $params[] = $search_param;
    }

    // Add sorting
    $query .= " ORDER BY $sort_by $sort_order";

    // Prepare and execute query
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get order counts for stats
    $total_orders = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    $pending_orders = $conn->query("SELECT COUNT(*) FROM orders WHERE payment_status = 'pending'")->fetchColumn();
    $completed_orders = $conn->query("SELECT COUNT(*) FROM orders WHERE payment_status = 'completed'")->fetchColumn();
    $cancelled_orders = $conn->query("SELECT COUNT(*) FROM orders WHERE payment_status = 'cancelled'")->fetchColumn();

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLONR - Order Management</title>
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
            <h1 class="admin-title">Order Management</h1>
            
            <!-- Order Statistics Cards -->
            <div class="stats-container">
                <div class="stat-card total">
                    <h3>Total Orders</h3>
                    <p><?= $total_orders ?></p>
                </div>
                <div class="stat-card pending">
                    <h3>Pending</h3>
                    <p><?= $pending_orders ?></p>
                </div>
                <div class="stat-card completed">
                    <h3>Completed</h3>
                    <p><?= $completed_orders ?></p>
                </div>
                <div class="stat-card cancelled">
                    <h3>Cancelled</h3>
                    <p><?= $cancelled_orders ?></p>
                </div>
            </div>
            
            <!-- Filter and Search Section -->
            <div class="filter-section">
                <form method="get" class="filter-form">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <option value="">All Orders</option>
                            <option value="pending" <?= $status_filter === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="completed" <?= $status_filter === 'completed' ? 'selected' : '' ?>>Completed</option>
                            <option value="cancelled" <?= $status_filter === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="search">Search</label>
                        <input type="text" name="search" id="search" placeholder="Order ID, name or email" value="<?= htmlspecialchars($search_query) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="sort">Sort By</label>
                        <select name="sort" id="sort">
                            <option value="placed_on" <?= $sort_by === 'placed_on' ? 'selected' : '' ?>>Date</option>
                            <option value="id" <?= $sort_by === 'id' ? 'selected' : '' ?>>Order ID</option>
                            <option value="name" <?= $sort_by === 'name' ? 'selected' : '' ?>>Customer Name</option>
                            <option value="total_price" <?= $sort_by === 'total_price' ? 'selected' : '' ?>>Amount</option>
                        </select>
                        <select name="order">
                            <option value="DESC" <?= $sort_order === 'DESC' ? 'selected' : '' ?>>Descending</option>
                            <option value="ASC" <?= $sort_order === 'ASC' ? 'selected' : '' ?>>Ascending</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="filter-btn">Apply Filters</button>
                    <a href="order.php" class="reset-btn">Reset</a>
                </form>
            </div>
            
            <!-- Orders Table -->
            <div class="orders-table-container">
                <?php if (empty($orders)): ?>
                    <div class="no-orders">
                        <i class="fas fa-box-open"></i>
                        <p>No orders found matching your criteria.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?= htmlspecialchars($order['id']) ?></td>
                                    <td>
                                        <div class="customer-info">
                                            <strong><?= htmlspecialchars($order['name']) ?></strong>
                                            <small><?= htmlspecialchars($order['email']) ?></small>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars(date('M d, Y h:i A', strtotime($order['placed_on']))) ?></td>
                                    <td>₱<?= htmlspecialchars(number_format($order['total_price'], 2)) ?></td>
                                    <td>
                                        <span class="status-badge status-<?= htmlspecialchars($order['payment_status']) ?>">
                                            <?= ucfirst(htmlspecialchars($order['payment_status'])) ?>
                                        </span>
                                    </td>
                                    <td class="actions">
                                        <a href="order_details.php?id=<?= $order['id'] ?>" class="action-btn view" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="update_order_status.php?id=<?= $order['id'] ?>" class="action-btn edit" title="Update Status">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($order['payment_status'] === 'pending'): ?>
                                        <a href="#" class="action-btn cancel" title="Cancel Order" onclick="confirmCancel(<?= $order['id'] ?>)">
                                            <i class="fas fa-times"></i>
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination (you can implement this later) -->
                    <div class="pagination">
                        <!-- Pagination links would go here -->
                    </div>
                <?php endif; ?>
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

    <script>
        function confirmCancel(orderId) {
            if (confirm('Are you sure you want to cancel this order?')) {
                window.location.href = 'update_order_status.php?id=' + orderId + '&status=cancelled';
            }
            return false;
        }
        
        // Add any additional JavaScript functionality here
        document.addEventListener('DOMContentLoaded', function() {
            // You can add sorting functionality or other interactive features here
        });
    </script>
</body>
</html>