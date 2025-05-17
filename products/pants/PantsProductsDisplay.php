<?php

include '../../db_connect.php'; 


if (!isset($conn) || !$conn) {
     error_log("Database connection is not established in jacketProductsDisplay.php");
     die("Database connection error. Please check logs.");
}



$productId = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $productId = (int) $_GET['id']; 
}


$product = null;
$allImages = []; 

if ($productId) {
    try {
        
        $sqlProduct = "SELECT id, name, description, price, category, image_url FROM products WHERE id = :id LIMIT 1";
        $stmtProduct = $conn->prepare($sqlProduct);
        $stmtProduct->execute([':id' => $productId]);
        $product = $stmtProduct->fetch(PDO::FETCH_ASSOC);

        
        if ($product) {
             
             if (!empty($product['image_url'])) {
                 $allImages[] = $product['image_url'];
             }

             
             $sqlImages = "SELECT image_url FROM product_images WHERE product_id = :product_id ORDER BY sort_order ASC, id ASC";
             $stmtImages = $conn->prepare($sqlImages);
             $stmtImages->execute([':product_id' => $productId]);
             $additionalImages = $stmtImages->fetchAll(PDO::FETCH_COLUMN);

             
             $allImages = array_merge($allImages, $additionalImages);

             
             if (empty($allImages)) {
                 
                 
                 $allImages = ['../path/to/placeholder_image.jpg']; 
             }
        }

    } catch (\PDOException $e) {
        error_log("Database Query Error in jacketProductsDisplay.php: " . $e->getMessage());
        die("Sorry, there was an error loading product details.");
    }
}


if (!$product) {
    http_response_code(404);
    echo "<h1>Product Not Found</h1>";
    echo "<p>The requested product could not be found.</p>";
    
    echo '<p><a href="jackets.php">Back to Jackets</a></p>'; 
    exit;
}


$productName = htmlspecialchars($product['name']);
$productPrice = number_format($product['price'], 2);
$productDescription = htmlspecialchars($product['description'] ?? ''); 
$productCategory = htmlspecialchars($product['category']);
$mainImageUrl = htmlspecialchars($allImages[0] ?? '../path/to/placeholder_image.jpg'); 





?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title><?php echo $productName; ?> - CLONR</title> <link rel="stylesheet" href="../../global.css"/> <link rel="stylesheet" href="../addtocart.css"> <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
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
              <?php include '../../header.php'; ?> </div>
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
            <option value="Small">Small</option>
            <option value="Medium">Medium</option>
            <option value="Large">Large</option>
            <option value="X-Large">X-Large</option>
            <option value="XX-Large">XX-Large</option>
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
          <button class="arrow left" onclick="prevSlide()">&#9664;</button>
          <img id="slider-image" src="<?php echo $mainImageUrl; ?>" alt="<?php echo $productName; ?>">
          <button class="arrow right" onclick="nextSlide()">&#9654;</button>
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
        
        const images = <?php echo json_encode($allImages); ?>;

        let currentIndex = 0;
        const sliderImage = document.getElementById('slider-image');

         
        if (images.length > 0) {
            sliderImage.src = images[currentIndex];
        } else {
             
             sliderImage.src = '../path/to/placeholder_image.jpg'; 
        }


        function prevSlide() {
          if (images.length > 0) {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            sliderImage.src = images[currentIndex];
          }
        }

        function nextSlide() {
          if (images.length > 0) {
            currentIndex = (currentIndex + 1) % images.length;
            sliderImage.src = images[currentIndex];
          }
        }
      </script>

      <script src="../../js/cart.js"></script>

      <?php
      
      $conn = null;
      ?>
    </body>
</html>