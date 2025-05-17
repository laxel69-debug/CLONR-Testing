<?php



include '../db_connect.php'; 


if (!isset($conn) || !$conn) {
     error_log("Database connection is not established in Jackets.php");
     die("Database connection error. Please check logs.");
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>T-Shirts - CLONR</title> <link rel="stylesheet" href="../global.css"/>
        <link rel="stylesheet" href="products.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
        </head>

    <body>
      <header>
           <div class="container">
              <a href="../index.php"><h1 class="title">CLONR</h1></a>
              <nav>
                <ul class="navbar">
                  <li><a href="../index.php">HOME</a></li>
                  <li class="dropdown">SHOP
                    <ul class="dropdown-menu">
                      <li><a href="tshirts.php">T-shirts</a></li>
                      <li><a href="jackets.php">Jackets</a></li>
                      <li><a href="pants.php">Pants</a></li>
                      <li><a href="shorts.php">Shorts</a></li>
                      <li><a href="accessories.php">Accessories</a></li>
                    </ul>
                  </li>
                  <li><a href="../sizechart.php">SIZE CHART</a></li>
                  <li><a href="../contact.php">CONTACT US</a></li>
                  <li><a href="payment/cart.php">CART</a></li>
                </ul>
              </nav>
              <div class="profile-container">
                <?php include '../header.php'; ?>
              </div>
            </div>
        </header>

        <section class="products-section">
            <div class="section-header">
                <h2>T-SHIRTS</h2> <select id="price-sort" class="price-sort">
                    <option value="default">Sort by Price</option>
                    <option value="low-to-high">Low to High</option>
                    <option value="high-to-low">High to Low</option>
                </select>
            </div>

            <div class="products-grid" id="products-grid">
                <?php
                
                $sql = "SELECT id, name, price, image_url FROM products WHERE category = :category";
                $stmt = $conn->prepare($sql); 

                try {
                    
                    $stmt->execute([':category' => 'T-shirts']);
                    $products = $stmt->fetchAll(PDO::FETCH_ASSOC); 
                } catch (\PDOException $e) {
                    error_log("Database Query Error in Jackets.php: " . $e->getMessage());
                    $products = []; 
                    echo "<p>Error loading jackets. Please try again later.</p>";
                }
                if ($products) { 
                    foreach ($products as $row) {
                        $productId = $row["id"];
                        
                        $productName = htmlspecialchars($row["name"]);
                        $productPrice = number_format($row["price"], 2); 
                        $rawPrice = $row["price"]; 
                        $productImage = htmlspecialchars($row["image_url"]); 

                        
                        echo '<div class="product-card" data-price="' . $rawPrice . '">';
                        
                        
                        echo '<a href="tshirts/TshirtsProductsDisplay.php?id=' . $productId . '">';
                        echo '<img src="' . $productImage . '" alt="' . $productName . '">';
                        
                        echo '<p>' . $productName . '<br>₱' . $productPrice . '</p>';
                        echo '</a>';
                        echo '</div>';
                    }
                } elseif (empty($products) && !isset($e)) {
                    
                    echo "<p>No T-Shirts products found.</p>";
                }
                ?>
            </div>

        </section>

        <hr class="custom-hr">

        <footer>
            <div class="footer-content">
                <h2>CLONR - Wear the Movement</h2>
                <p>Email: customerservice.CLONR@gmail.com | Phone: +63 XXX XXX XXXX</p>
                <p>© 2025 CLONR. All Rights Reserved.</p>
            </div>
        </footer>

        <script>
          document.getElementById('price-sort').addEventListener('change', function() {
            const productsGrid = document.getElementById('products-grid');
            const products = Array.from(productsGrid.getElementsByClassName('product-card'));
            const sortOption = this.value;

            if (sortOption === 'low-to-high') {
              products.sort((a, b) => {
                const priceA = parseFloat(a.getAttribute('data-price'));
                const priceB = parseFloat(b.getAttribute('data-price'));
                return priceA - priceB;
              });
            } else if (sortOption === 'high-to-low') {
              products.sort((a, b) => {
                const priceA = parseFloat(a.getAttribute('data-price'));
                const priceB = parseFloat(b.getAttribute('data-price'));
                return priceB - priceA;
              });
            }

            
            products.forEach(product => productsGrid.appendChild(product));
          });
           
           
           
        </script>

        <?php
        
        $conn = null;
        ?>
    </body>
</html>
