<?php
session_start();

// Database connection
$db_name = "mysql:host=localhost;dbname=clonr_db";
$username = "root";
$password = "";
$conn = new PDO($db_name, $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check admin authentication
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

// Search and filter functionality
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? 'all';
$page = $_GET['page'] ?? 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Base query
$query = "SELECT u.*, 
          (SELECT COUNT(*) FROM orders WHERE user_id = u.id) as order_count,
          (SELECT COUNT(*) FROM messages WHERE user_id = u.id AND is_read = 0) as unread_messages
          FROM users u";

// Where conditions
$conditions = [];
$params = [];

if (!empty($search)) {
    $conditions[] = "(u.name LIKE ? OR u.email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($status_filter !== 'all') {
    $conditions[] = "u.status = ?";
    $params[] = $status_filter;
}

// Add conditions to query
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

// Get total users for pagination
$countQuery = "SELECT COUNT(*) FROM ($query) as total";
$countStmt = $conn->prepare($countQuery);
$countStmt->execute($params);
$totalUsers = $countStmt->fetchColumn();
$totalPages = ceil($totalUsers / $limit);



// Get users
$stmt = $conn->prepare($query);
$stmt->execute($params);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLONR - User Management</title>
    <link rel="stylesheet" href="../global.css">
    <link rel="stylesheet" href="admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Enhanced styles for user management */
        .user-management-tools {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .search-filter-container {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            flex: 1;
            min-width: 300px;
        }
        
        .search-box {
            flex: 1;
            min-width: 250px;
            position: relative;
        }
        
        .search-box input {
            width: 100%;
            padding: 10px 15px;
            padding-left: 40px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
        }
        
        .filter-box {
            min-width: 150px;
        }
        
        .filter-box select {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 5px;
        }
        
        .pagination a, .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
        }
        
        .pagination a:hover {
            background-color: #f0f0f0;
        }
        
        .pagination .current {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        
        .user-actions {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        
        .action-btn {
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .action-btn i {
            font-size: 12px;
        }
        
        .view { background-color: #17a2b8; color: white; }
        .edit { background-color: #ffc107; color: black; }
        .message { background-color: #28a745; color: white; }
        .delete { background-color: #dc3545; color: white; }
        .export { background-color: #6c757d; color: white; }
        .add-new { background-color: #007bff; color: white; }
        
        .status-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-badge.active { background-color: #d4edda; color: #155724; }
        .status-badge.inactive { background-color: #f8d7da; color: #721c24; }
        .status-badge.banned { background-color: #fff3cd; color: #856404; }
        
        .user-avatar {
            border-radius: 50%;
            object-fit: cover;
            height: 40px;
            width: 40px;
        }
        
        .stats-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            background-color: #e9ecef;
            color: #495057;
            font-size: 12px;
            margin-left: 5px;
        }
        
        @media (max-width: 768px) {
            .users-table table, .users-table thead, .users-table tbody, 
            .users-table th, .users-table td, .users-table tr {
                display: block;
            }
            
            .users-table thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            
            .users-table tr {
                margin-bottom: 15px;
                border: 1px solid #ddd;
                border-radius: 4px;
                padding: 10px;
            }
            
            .users-table td {
                border: none;
                position: relative;
                padding-left: 50%;
            }
            
            .users-table td:before {
                position: absolute;
                left: 10px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: bold;
            }
            
            .users-table td:nth-of-type(1):before { content: "ID"; }
            .users-table td:nth-of-type(2):before { content: "Profile"; }
            .users-table td:nth-of-type(3):before { content: "Name"; }
            .users-table td:nth-of-type(4):before { content: "Email"; }
            .users-table td:nth-of-type(5):before { content: "Joined"; }
            .users-table td:nth-of-type(6):before { content: "Orders"; }
            .users-table td:nth-of-type(7):before { content: "Messages"; }
            .users-table td:nth-of-type(8):before { content: "Status"; }
            .users-table td:nth-of-type(9):before { content: "Actions"; }
            
            .user-actions {
                justify-content: flex-start;
            }
        }
    </style>
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
            <h1 class="admin-title">User Management</h1>
            
            <div class="user-management-tools">
                <div class="search-filter-container">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <form method="GET" action="users.php">
                            <input type="text" name="search" placeholder="Search users..." 
                                   value="<?= htmlspecialchars($search) ?>">
                            <input type="hidden" name="status" value="<?= htmlspecialchars($status_filter) ?>">
                        </form>
                    </div>
                    
                    <div class="filter-box">
                        <form method="GET" action="users.php">
                            <select name="status" onchange="this.form.submit()">
                                <option value="all" <?= $status_filter === 'all' ? 'selected' : '' ?>>All Statuses</option>
                                <option value="active" <?= $status_filter === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= $status_filter === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                <option value="banned" <?= $status_filter === 'banned' ? 'selected' : '' ?>>Banned</option>
                            </select>
                            <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                        </form>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <a href="export_users.php?search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>" 
                       class="action-btn export">
                        <i class="fas fa-file-export"></i> Export
                    </a>
                    <a href="add_user.php" class="action-btn add-new">
                        <i class="fas fa-plus"></i> Add New
                    </a>
                </div>
            </div>
            
            <div class="users-table">
                <?php if (empty($users)): ?>
                    <div class="no-results">
                        <i class="fas fa-user-slash" style="font-size: 24px; margin-bottom: 10px;"></i>
                        <p>No users found matching your criteria.</p>
                        <a href="users.php" class="action-btn">Reset Filters</a>
                    </div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Profile</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined</th>
                                <th>Orders</th>
                                <th>Messages</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['id']) ?></td>
                                <td>
                                    <img src="../uploaded_img/<?= htmlspecialchars($user['image'] ?? 'default.png') ?>" 
                                         alt="<?= htmlspecialchars($user['name']) ?>" class="user-avatar">
                                </td>
                                <td>
                                    <?= htmlspecialchars($user['name']) ?>
                                    <?php if ($user['is_admin']): ?>
                                        <span class="stats-badge" title="Administrator">ADMIN</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars(date('M d, Y', strtotime($user['created_at']))) ?></td>
                                <td>
                                    <span class="stats-badge"><?= $user['order_count'] ?></span>
                                </td>
                                <td>
                                    <?php if ($user['unread_messages'] > 0): ?>
                                        <span class="stats-badge" style="background-color: #dc3545; color: white;">
                                            <?= $user['unread_messages'] ?> new
                                        </span>
                                    <?php else: ?>
                                        <span class="stats-badge">0</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="status-badge <?= htmlspecialchars($user['status']) ?>">
                                        <?= ucfirst(htmlspecialchars($user['status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="user-actions">
                                        <a href="view_user.php?id=<?= $user['id'] ?>" class="action-btn view" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="edit_user.php?id=<?= $user['id'] ?>" class="action-btn edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="message_user.php?id=<?= $user['id'] ?>" class="action-btn message" title="Message">
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                        <?php if ($user['status'] === 'banned'): ?>
                                            <a href="unban_user.php?id=<?= $user['id'] ?>" class="action-btn edit" title="Unban">
                                                <i class="fas fa-unlock"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="ban_user.php?id=<?= $user['id'] ?>" class="action-btn delete" title="Ban">
                                                <i class="fas fa-ban"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="delete_user.php?id=<?= $user['id'] ?>" class="action-btn delete" 
                                           title="Delete" onclick="return confirmDelete()">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        <?php endif; ?>
                        
                        <?php 
                        // Show limited pagination links
                        $start = max(1, $page - 2);
                        $end = min($totalPages, $page + 2);
                        
                        if ($start > 1) {
                            echo '<a href="?page=1&search=' . urlencode($search) . '&status=' . urlencode($status_filter) . '">1</a>';
                            if ($start > 2) echo '<span>...</span>';
                        }
                        
                        for ($i = $start; $i <= $end; $i++): ?>
                            <?php if ($i == $page): ?>
                                <span class="current"><?= $i ?></span>
                            <?php else: ?>
                                <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        if ($end < $totalPages) {
                            if ($end < $totalPages - 1) echo '<span>...</span>';
                            echo '<a href="?page=' . $totalPages . '&search=' . urlencode($search) . '&status=' . urlencode($status_filter) . '">' . $totalPages . '</a>';
                        }
                        ?>
                        
                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>">
                                Next <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
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
        // Auto-submit search form when typing stops
        let searchTimer;
        const searchInput = document.querySelector('input[name="search"]');
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
        
        // Confirm before important actions
        function confirmDelete() {
            return confirm('Are you sure you want to permanently delete this user? This action cannot be undone.');
        }
        
        function confirmBan() {
            return confirm('Are you sure you want to ban this user? They will no longer be able to access their account.');
        }
        
        // Add click handlers for ban/unban links
        document.querySelectorAll('a[href*="ban_user.php"], a[href*="unban_user.php"]').forEach(link => {
            link.addEventListener('click', function(e) {
                const action = this.href.includes('ban_user.php') ? 'ban' : 'unban';
                const msg = action === 'ban' 
                    ? 'Are you sure you want to ban this user? They will no longer be able to access their account.'
                    : 'Are you sure you want to unban this user? They will regain access to their account.';
                
                if (!confirm(msg)) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>