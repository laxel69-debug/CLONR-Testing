<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>CLONR</title>
        <link rel="stylesheet" href="global.css"/>
        <link rel="stylesheet" href="sizechart.css">
        
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
                <li><a href="products/payment/cart.php">CART  </a></li>
              </ul>
            </nav>
            <div class="profile-container">
              <?php include 'header.php'; ?>
            </div>
          </div>
        </header>
          
        <div class="size">
          <img src="https://cdn.shopify.com/s/files/1/0248/7910/4072/files/regular_fit_size_chart-01_600x600.png?v=1727776631" alt="tshirt">

          <img src="https://cdn.shopify.com/s/files/1/0248/7910/4072/files/HOODIE_e3c867b9-1d27-4c07-a6d3-7a5b6c5566c8_600x600.jpg?v=1704337237" alt="jackets">

          <img src="https://dbtkco.com/cdn/shop/files/SHORTS-MESH_1.jpg?v=1740745225" alt="shorts">

          <img src="https://dbtkco.com/cdn/shop/files/FP-SIZECHART-07.png?v=1728696742" alt="pants">

        </div>

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