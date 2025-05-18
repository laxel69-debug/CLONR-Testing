<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>CLONR</title>
        <link rel="stylesheet" href="../../global.css"/>
        <link rel="stylesheet" href="../addtocart.css">
        
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
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
                <li><a href="../payment/cart.php">CART (<span class="cart-count">0</span>)</a></li>
              </ul>
            </nav>
            <div class="profile-container">
              <?php include '../../header.php'; ?>
            </div>
          </div>
      </header>

      <section class="product-section">
        <div class="product-details">
          <h2>PANTS</h2>
          <h1>SPARK PANELED WIDE PANTS - BLUEBERR</h1>
          <p>₱2,500.00</p>
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
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
            <option>5</option>
          </select>
    
          <button id="add-to-cart">Add to Cart</button>
        </div>
    
        <div class="product-slider">
          <button class="arrow left" onclick="prevSlide()">&#9664;</button>
          <img id="slider-image" src="https://dbtkco.com/cdn/shop/files/SPARK_PANELED_WIDE_PANTS_1.jpg?v=1737105339" alt="Cipher Tee">
          <button class="arrow right" onclick="nextSlide()">&#9654;</button>
        </div>
      </section>

      <script>
        const images = [
        "https://dbtkco.com/cdn/shop/files/SPARK_PANELED_WIDE_PANTS_1.jpg?v=1737105339",
        "https://dbtkco.com/cdn/shop/files/SPARK_PANELED_WIDE_PANTS_2.jpg?v=1737105339",
        "https://dbtkco.com/cdn/shop/files/DB101676.webp?v=1737105339"
        ];

        let currentIndex = 0;
        const sliderImage = document.getElementById('slider-image');

        function prevSlide() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        sliderImage.src = images[currentIndex];
        }

        function nextSlide() {
        currentIndex = (currentIndex + 1) % images.length;
        sliderImage.src = images[currentIndex];
        }
      </script>

      <hr class="custom-hr">

      <footer>
        <div class="footer-content">
            <h2>CLONR - Wear the Movement</h2>
            <p>Email: customerservice.CLONR@gmail.com | Phone: +63 XXX XXX XXXX</p>
            <p>© 2025 CLONR. All Rights Reserved.</p>
        </div>
      </footer>

      <!-- Include the cart.js file -->
      <script src="../../js/cart.js"></script>
    </body>
</html>