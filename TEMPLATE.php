<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>CLONR</title>
        <link rel="stylesheet" href="global.css"/>
        <link rel="stylesheet" href=".css">
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
                <li><a href="products/payment/cart.php">CART (<span class="cart-count">0</span>)</a></li>
              </ul>
            </nav>
            <div class="profile-container">
              <?php include 'header.php'; ?>
            </div>
          </div>
        </header>
          
        <hr class="custom-hr">
        <main>
                    <div class="toggle-buttons" role="tablist" aria-label="Toggle purchase history between completed and canceled">
                      <button id="completedTab" role="tab" aria-selected="true" aria-controls="purchaseTable" class="active" tabindex="0">Completed</button>
                      <button id="canceledTab" role="tab" aria-selected="false" aria-controls="purchaseTable" tabindex="-1">Canceled</button>
                    </div>

                    <div class="filter-container">
                      <label for="categoryFilter">Filter by Clothes Category:</label>
                      <select id="categoryFilter" aria-controls="purchaseTable">
                        <option value="all">All</option>
                        <option value="shirts">Shirts</option>
                        <option value="jackets">Jackets</option>
                        <option value="pants">Pants</option>
                        <option value="accessories">Accessories</option>
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
                        </tr>
                      </thead>
                      <tbody>
                        <!-- Purchase rows inserted here -->
                      </tbody>
                    </table>
                    <p class="no-records" id="noRecords" style="display:none;">No purchase records found.</p>
                  </main>

                  <script>
                    // Same backend integration notes as in previous version, omitted here for brevity.

                    // Sample fallback data if PHP doesn't inject data
                    const completedPurchases = window.completedPurchases || [
                      { id: 1, date: '2024-06-01', name: 'Blue Denim Jacket', category: 'jackets', quantity: 1, price: 79.99, photoUrl: 'https://via.placeholder.com/50?text=Jacket' },
                      { id: 2, date: '2024-05-20', name: 'Classic White Shirt', category: 'shirts', quantity: 2, price: 39.98, photoUrl: 'https://via.placeholder.com/50?text=Shirt' },
                      { id: 3, date: '2024-05-15', name: 'Black Chinos Pants', category: 'pants', quantity: 1, price: 59.95, photoUrl: 'https://via.placeholder.com/50?text=Pants' },
                      { id: 4, date: '2024-05-08', name: 'Leather Belt', category: 'accessories', quantity: 1, price: 24.50, photoUrl: 'https://via.placeholder.com/50?text=Belt' },
                      { id: 5, date: '2024-04-30', name: 'Plaid Shirt', category: 'shirts', quantity: 1, price: 29.95, photoUrl: 'https://via.placeholder.com/50?text=Shirt' }
                    ];

                    const canceledPurchases = window.canceledPurchases || [
                      { id: 101, date: '2024-05-18', name: 'Bomber Jacket', category: 'jackets', quantity: 1, price: 89.50, photoUrl: 'https://via.placeholder.com/50?text=Jacket' },
                      { id: 102, date: '2024-05-10', name: 'Slim Fit Pants', category: 'pants', quantity: 2, price: 99.90, photoUrl: 'https://via.placeholder.com/50?text=Pants' },
                      { id: 103, date: '2024-04-30', name: 'Winter Scarf', category: 'accessories', quantity: 1, price: 19.99, photoUrl: 'https://via.placeholder.com/50?text=Scarf' },
                      { id: 104, date: '2024-04-25', name: 'Denim Shirt', category: 'shirts', quantity: 1, price: 34.99, photoUrl: 'https://via.placeholder.com/50?text=Shirt' }
                    ];

                    // Utilities
                    function formatPrice(price) {
                      return '$' + price.toFixed(2);
                    }
                    function capitalize(str) {
                      return str.charAt(0).toUpperCase() + str.slice(1);
                    }

                    // DOM elements
                    const completedTab = document.getElementById('completedTab');
                    const canceledTab = document.getElementById('canceledTab');
                    const categoryFilter = document.getElementById('categoryFilter');
                    const tbody = document.querySelector('#purchaseTable tbody');
                    const noRecords = document.getElementById('noRecords');

                    // State
                    let currentTab = 'completed'; // 'completed' or 'canceled'

                    // Render function with animation triggers
                    function renderTable() {
                      tbody.innerHTML = '';
                      const data = currentTab === 'completed' ? completedPurchases : canceledPurchases;
                      const filter = categoryFilter.value;

                      const filtered = filter === 'all' ? data : data.filter(p => p.category === filter);

                      if (filtered.length === 0) {
                        noRecords.classList.add('visible');
                        return;
                      } else {
                        noRecords.classList.remove('visible');
                      }

                      filtered.forEach((purchase, index) => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                          <td class="photo-cell" data-label="Photo">
                            <img src="${purchase.photoUrl}" alt="Photo of ${purchase.name}" loading="lazy" />
                          </td>
                          <td data-label="Purchase Date">${purchase.date}</td>
                          <td data-label="Product Name">${purchase.name}</td>
                          <td data-label="Category">${capitalize(purchase.category)}</td>
                          <td data-label="Quantity">${purchase.quantity}</td>
                          <td data-label="Price" class="price">${formatPrice(purchase.price)}</td>
                        `;
                        tbody.appendChild(tr);
                        // Animate row with staggered delays
                        setTimeout(() => tr.classList.add('visible'), index * 100);
                      });
                    }

                    // Update tab styles and ARIA attributes
                    function updateTabs() {
                      if (currentTab === 'completed') {
                        completedTab.classList.add('active');
                        completedTab.setAttribute('aria-selected', 'true');
                        completedTab.setAttribute('tabindex', '0');
                        canceledTab.classList.remove('active');
                        canceledTab.setAttribute('aria-selected', 'false');
                        canceledTab.setAttribute('tabindex', '-1');
                      } else {
                        canceledTab.classList.add('active');
                        canceledTab.setAttribute('aria-selected', 'true');
                        canceledTab.setAttribute('tabindex', '0');
                        completedTab.classList.remove('active');
                        completedTab.setAttribute('aria-selected', 'false');
                        completedTab.setAttribute('tabindex', '-1');
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
                    canceledTab.addEventListener('click', () => {
                      if (currentTab !== 'canceled') {
                        currentTab = 'canceled';
                        updateTabs();
                        renderTable();
                      }
                    });
                    categoryFilter.addEventListener('change', renderTable);

                    // Initial render
                    updateTabs();
                    renderTable();
                  </script>


        <footer>
        <div class="footer-content">
            <h2>CLONR - Wear the Movement</h2>
            <p>Email: noreply.CLONR@gmail.com | Phone: +63 XXX XXX XXXX</p>
            <p>© 2025 CLONR. All Rights Reserved.</p>
        </div>
        </footer>
    </body>
</html>