<?php
// --- Database Connection (Using PDO) ---
// Assuming db_connect.php exists in your root directory
// If your db credentials are not in a separate file,
// include the PDO connection code directly here as in Accessories.php
// and adjust paths if necessary.
include '../../config.php'; // Adjust path as needed if db_connect.php is elsewhere

// Use the $conn variable established in db_connect.php
if (!isset($conn) || !$conn) {
     error_log("Database connection is not established in AccessoriesProductsDisplay.php");
     die("Database connection error. Please check logs.");
}
// --- End Database Connection ---

// --- Get Product ID from URL ---
$productId = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $productId = (int) $_GET['id']; // Sanitize by casting to integer
}

// --- Fetch Product Details ---
$product = null;
if ($productId) {
    $sql = "SELECT id, name, description, price, category, image_url FROM products WHERE id = :id LIMIT 1";
    $stmt = $conn->prepare($sql);

    try {
        $stmt->execute([':id' => $productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch a single row
    } catch (\PDOException $e) {
        error_log("Database Query Error in AccessoriesProductsDisplay.php: " . $e->getMessage());
        // You might show a generic error or redirect
        die("Sorry, there was an error loading product details.");
    }
}

// --- Handle Product Not Found ---
if (!$product) {
    // No product found with that ID, or ID was invalid/missing
    // You can show a 404 page, redirect, or display an error message
    http_response_code(404); // Set HTTP status code to 404 Not Found
    echo "<h1>Product Not Found</h1>";
    echo "<p>The requested product could not be found.</p>";
    // Optionally include header/footer or a link back to the accessories list
    echo '<p><a href="accessories.php">Back to Accessories</a></p>';
    exit; // Stop script execution
}

// Product found, assign data to variables for easier use in HTML
$productName = htmlspecialchars($product['name']);
$productPrice = number_format($product['price'], 2); // Format price
$productDescription = htmlspecialchars($product['description']); // Assuming description can exist
$productCategory = htmlspecialchars($product['category']);
$productImage = htmlspecialchars($product['image_url']);

// Since sizes_available was removed for the prototype, we'll remove the size dropdown.
// Keep Quantity and Add to Cart button.
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title><?php echo $productName; ?> - CLONR</title> <link rel="stylesheet" href="../../global.css"/>
        <link rel="stylesheet" href="../addtocart.css"> <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
         <link rel="stylesheet" href="product_detail.css">
    </head>

    <body>
    <header>
          <div class="container">
            <a href="../../main.php"><h1 class="title">CLONR</h1></a>
            <nav>
              <ul class="navbar">
                <li><a href="../../main.php">HOME</a></li>
                <li class="dropdown">SHOP
                  <ul class="dropdown-menu">
                    <li><a href="../tshirts.php">T-shirts</a></li>
                    <li><a href="../jackets.php">Jackets</a></li>
                    <li><a href="../pants.php">Pants</a></li>
                    <li><a href="../shorts.php">Shorts</a></li>
                    <li><a href="../accessories.php">Accessories</a></li>
                  </ul>
                </li>
                <li><a href="../../sizechart.php">SIZE CHART</a></li>
                <li><a href="../../contact.php">CONTACT US</a></li>
                <li><a href="../payment/cart.php">CART</a></li>
              </ul>
            </nav>
            <div class="profile-container">
              <?php include '../../header.php'; ?>
            </div>
          </div>
      </header>

      <section class="product-section">
        <div class="product-details">
          <h2><?php echo $productCategory; ?></h2> <h1><?php echo $productName; ?></h1> <p>₱<?php echo $productPrice; ?></p> <?php if (!empty($productDescription)): ?>
              <div class="product-description">
                  <h3>Description</h3>
                  <p><?php echo $productDescription; ?></p> </div>
          <?php endif; ?>

        <label for="size">Size:</label>
          <select id="size">
            <option>Small</option>
            <option>Medium</option>
            <option>Large</option>
            <option>X-Large</option>
            <option>XX-Large</option>
          </select>

          <label for="quantity">Quantity:</label>
          <select id="quantity">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            </select>

          <button id="add-to-cart" data-product-id="<?php echo $productId; ?>">Add to Cart</button>
        </div>

        <div class="product-slider">
          <img id="slider-image" src="<?php echo $productImage; ?>" alt="<?php echo $productName; ?>"> </div>
      </section>

      <hr class="custom-hr">

      <footer>
        <div class="footer-content">
            <h2>CLONR - Wear the Movement</h2>
            <p>Email: customerservice.CLONR@gmail.com | Phone: +63 XXX XXX XXXX</p>
            <p>© 2025 CLONR. All Rights Reserved.</p>
        </div>
      </footer>

      <script src="../../js/cart.js"></script>

      <?php
      // Close the database connection
      $conn = null;
      ?>
    </body>
</html>