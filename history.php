<?php
@include 'config.php';
session_start();

if(!isset($_SESSION['user_id'])){
   header('location:login.php');
   exit;
}

// Fetch completed orders
$completed_query = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? AND payment_status = 'completed' ORDER BY placed_on DESC");
$completed_query->execute([$_SESSION['user_id']]);
$completed_orders = $completed_query->fetchAll(PDO::FETCH_ASSOC);

// Fetch canceled orders
$canceled_query = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? AND payment_status = 'canceled' ORDER BY placed_on DESC");
$canceled_query->execute([$_SESSION['user_id']]);
$canceled_orders = $canceled_query->fetchAll(PDO::FETCH_ASSOC);

// Fetch pending orders
$pending_query = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? AND payment_status = 'pending' ORDER BY placed_on DESC");
$pending_query->execute([$_SESSION['user_id']]);
$pending_orders = $pending_query->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for JavaScript
$completed_data = [];
foreach($completed_orders as $order) {
    $products = explode(', ', $order['total_products']);
    foreach($products as $product) {
        preg_match('/(.*) \((\d+)\)/', $product, $matches);
        if(count($matches) === 3) {
            // You'll need to get the actual category and image from your database
            $category = 'shirts'; // Placeholder - replace with actual category lookup
            $image = 'https://via.placeholder.com/50?text=Product';
            $completed_data[] = [
                'id' => $order['id'],
                'date' => date('Y-m-d', strtotime($order['placed_on'])),
                'name' => $matches[1],
                'category' => $category,
                'quantity' => $matches[2],
                'price' => $order['total_price'] / count($products),
                'photoUrl' => $image,
                'status' => 'completed'
            ];
        }
    }
}

$canceled_data = [];
foreach($canceled_orders as $order) {
    $products = explode(', ', $order['total_products']);
    foreach($products as $product) {
        preg_match('/(.*) \((\d+)\)/', $product, $matches);
        if(count($matches) === 3) {
            $category = 'shirts'; // Placeholder - replace with actual category lookup
            $image = 'https://via.placeholder.com/50?text=Product';
            $canceled_data[] = [
                'id' => $order['id'],
                'date' => date('Y-m-d', strtotime($order['placed_on'])),
                'name' => $matches[1],
                'category' => $category,
                'quantity' => $matches[2],
                'price' => $order['total_price'] / count($products),
                'photoUrl' => $image,
                'status' => 'canceled'
            ];
        }
    }
}

$pending_data = [];
foreach($pending_orders as $order) {
    $products = explode(', ', $order['total_products']);
    foreach($products as $product) {
        preg_match('/(.*) \((\d+)\)/', $product, $matches);
        if(count($matches) === 3) {
            $category = 'shirts'; // Placeholder - replace with actual category lookup
            $image = 'https://via.placeholder.com/50?text=Product';
            $pending_data[] = [
                'id' => $order['id'],
                'date' => date('Y-m-d', strtotime($order['placed_on'])),
                'name' => $matches[1],
                'category' => $category,
                'quantity' => $matches[2],
                'price' => $order['total_price'] / count($products),
                'photoUrl' => $image,
                'status' => 'pending'
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>CLONR - Purchase History</title>
    <link rel="stylesheet" href="global.css">
    <link rel="stylesheet" href="history.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
</head>
    
<body>
    <header>
        <div class="container">
        <a href="main.php"><h1 class="title">CLONR</h1></a>
            <nav>
                <ul class="navbar">
                    <li><a href="main.php">HOME</a></li>
                    <li class="dropdown">SHOP
                        <ul class="dropdown-menu">
                            <li><a href="products/tshirts.php">T-shirts</a></li>
                            <li><a href="products/jackets.php">Jackets</a></li>
                            <li><a href="products/pants.php">Pants</a></li>
                            <li><a href="products/shorts.php">Shorts</a></li>
                            <li><a href="products/accessories.php">Accessories</a></li>
                        </ul>
                    </li>
                    <li><a href="sizechart.php">SIZE CHART</a></li>
                    <li><a href="contact.php">CONTACT US</a></li>
                    <li><a href="products/payment/cart.php">CART</a></li>
                </ul>
            </nav>
            <div class="profile-container">
                <?php include 'header.php'; ?>
            </div>
        </div>
    </header>
   
    <main>
        <div class="toggle-buttons" role="tablist" aria-label="Toggle purchase history between completed, pending and canceled">
            <button id="completedTab" role="tab" aria-selected="true" aria-controls="purchaseTable" class="active" tabindex="0">Completed</button>
            <button id="pendingTab" role="tab" aria-selected="false" aria-controls="purchaseTable" tabindex="-1">Pending</button>
            <button id="canceledTab" role="tab" aria-selected="false" aria-controls="purchaseTable" tabindex="-1">Canceled</button>
        </div>

        <div class="filter-container">
            <label for="categoryFilter">Filter by Category:</label>
            <select id="categoryFilter" aria-controls="purchaseTable">
                <option value="all">All</option>
                <option value="shirts">Shirts</option>
                <option value="jackets">Jackets</option>
                <option value="pants">Pants</option>
                <option value="accessories">Accessories</option>
            </select>
            
            <label for="statusFilter">Filter by Status:</label>
            <select id="statusFilter" aria-controls="purchaseTable">
                <option value="all">All</option>
                <option value="completed">Completed</option>
                <option value="pending">Pending</option>
                <option value="canceled">Canceled</option>
            </select>
        </div>

        <table id="purchaseTable" aria-label="Purchase History Table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Purchase Date</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Purchase rows will be inserted here by JavaScript -->
            </tbody>
        </table>
        <p class="no-records" id="noRecords" style="display:none;">No purchase records found.</p>
    </main>

    <script>
        // Inject PHP data into JavaScript
        const completedPurchases = <?php echo json_encode($completed_data); ?>;
        const pendingPurchases = <?php echo json_encode($pending_data); ?>;
        const canceledPurchases = <?php echo json_encode($canceled_data); ?>;

        // Combine all purchases for filtering
        const allPurchases = [...completedPurchases, ...pendingPurchases, ...canceledPurchases];

        // Utilities
        function formatPrice(price) {
            return '₱' + price.toFixed(2);
        }
        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
        function getStatusClass(status) {
            return `status-${status}`;
        }
        function getStatusText(status) {
            return capitalize(status);
        }

        // DOM elements
        const completedTab = document.getElementById('completedTab');
        const pendingTab = document.getElementById('pendingTab');
        const canceledTab = document.getElementById('canceledTab');
        const categoryFilter = document.getElementById('categoryFilter');
        const statusFilter = document.getElementById('statusFilter');
        const tbody = document.querySelector('#purchaseTable tbody');
        const noRecords = document.getElementById('noRecords');

        // State
        let currentTab = 'completed'; // 'completed', 'pending', or 'canceled'

        // Render function
        function renderTable() {
            tbody.innerHTML = '';

            // Determine which data to use based on the current tab
            let data;
            if (currentTab === 'completed') {
                data = completedPurchases;
            } else if (currentTab === 'pending') {
                data = pendingPurchases;
            } else if (currentTab === 'canceled') {
                data = canceledPurchases;
            } else {
                data = allPurchases;
            }

            const categoryFilterValue = categoryFilter.value;
            const statusFilterValue = statusFilter.value;

            const filtered = data.filter(p => {
                const categoryMatch = categoryFilterValue === 'all' || p.category === categoryFilterValue;
                const statusMatch = statusFilterValue === 'all' || p.status === statusFilterValue;
                return categoryMatch && statusMatch;
            });

            if (filtered.length === 0) {
                noRecords.style.display = 'block';
                return;
            } else {
                noRecords.style.display = 'none';
            }

            filtered.forEach((purchase, index) => {
                const tr = document.createElement('tr');
                
                // Add receipt link only for completed orders
                const receiptLink = purchase.status === 'completed' ? 
                    `<a href="/clonr/products/payment/receipt.php?order_id=${purchase.id}" class="receipt-link" target="_blank">
                        <i class="bi bi-receipt"></i> Receipt
                    </a>` : '';
                
                // Add cancel button for pending orders
                const cancelButton = purchase.status === 'pending' ?
                    `<button class="action-button cancel-button" data-order-id="${purchase.id}">
                        <i class="bi bi-x-circle"></i> Cancel
                    </button>` : '';
                
                tr.innerHTML = `
                    <td class="photo-cell" data-label="Photo">
                        <img src="${purchase.photoUrl}" alt="Photo of ${purchase.name}" loading="lazy" />
                    </td>
                    <td data-label="Purchase Date">${purchase.date}</td>
                    <td data-label="Product Name">${purchase.name}</td>
                    <td data-label="Category">${capitalize(purchase.category)}</td>
                    <td data-label="Quantity">${purchase.quantity}</td>
                    <td data-label="Price" class="price">${formatPrice(purchase.price)}</td>
                    <td data-label="Status">
                        <span class="status-badge ${getStatusClass(purchase.status)}">
                            ${getStatusText(purchase.status)}
                        </span>
                    </td>
                    <td data-label="Actions">
                        ${receiptLink}
                        ${cancelButton}
                    </td>
                `;
                tbody.appendChild(tr);
            });

            // Add event listeners to cancel buttons
            document.querySelectorAll('.cancel-button').forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-order-id');
                    if (confirm('Are you sure you want to cancel this order?')) {
                        cancelOrder(orderId);
                    }
                });
            });
        }

        // Function to handle order cancellation
        function cancelOrder(orderId) {
            fetch('cancel_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ order_id: orderId }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Order canceled successfully');
                    location.reload(); // Refresh to show updated status
                } else {
                    alert('Error canceling order: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while canceling the order');
            });
        }

        // Update tab styles and ARIA attributes
        function updateTabs() {
            [completedTab, pendingTab, canceledTab].forEach(tab => {
                tab.classList.remove('active');
                tab.setAttribute('aria-selected', 'false');
                tab.setAttribute('tabindex', '-1');
            });

            if (currentTab === 'completed') {
                completedTab.classList.add('active');
                completedTab.setAttribute('aria-selected', 'true');
                completedTab.setAttribute('tabindex', '0');
            } else if (currentTab === 'pending') {
                pendingTab.classList.add('active');
                pendingTab.setAttribute('aria-selected', 'true');
                pendingTab.setAttribute('tabindex', '0');
            } else if (currentTab === 'canceled') {
                canceledTab.classList.add('active');
                canceledTab.setAttribute('aria-selected', 'true');
                canceledTab.setAttribute('tabindex', '0');
            }
        }

        // Event listeners
        completedTab.addEventListener('click', () => {
            if (currentTab !== 'completed') {
                currentTab = 'completed';
                updateTabs();
                renderTable();
            }
        });
        
        pendingTab.addEventListener('click', () => {
            if (currentTab !== 'pending') {
                currentTab = 'pending';
                updateTabs();
                renderTable();
            }
        });
        
        canceledTab.addEventListener('click', () => {
            if (currentTab !== 'canceled') {
                currentTab = 'canceled';
                updateTabs();
                renderTable();
            }
        });
        
        categoryFilter.addEventListener('change', renderTable);
        statusFilter.addEventListener('change', renderTable);

        // Initial render
        updateTabs();
        renderTable();
    </script>
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