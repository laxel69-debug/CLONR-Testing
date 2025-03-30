<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['send'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg'];
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   $select_message = $conn->prepare("SELECT * FROM `message` WHERE name = ? AND email = ? AND message = ?");
   $select_message->execute([$name, $email, $msg]);

   if($select_message->rowCount() > 0){
      $message[] = 'You have already sent this message!';
   }else{

      $insert_message = $conn->prepare("INSERT INTO `message`(user_id, name, email, message) VALUES(?,?,?,?)");
      $insert_message->execute([$user_id, $name, $email, $msg]);

      $message[] = 'Message sent successfully!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>CLONR</title>
        <link rel="stylesheet" href="global.css"/>
        <link rel="stylesheet" href="contact.css">
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
          
        <section class="contact">

          <h1 class="title">Message Us!</h1>

          <?php
            if (isset($message)) {
                foreach ($message as $msg) {
                    echo "<div class='message'>$msg</div>";
                }
            }
            ?>

          <form action="" method="POST">
              <input type="text" name="name" class="box" required placeholder="Enter your name">
              <input type="email" name="email" class="box" required placeholder="Enter your email">
              <textarea name="msg" class="box" required placeholder="Enter your message" cols="30" rows="10"></textarea>
              <input type="submit" value="Send Message" class="btn" name="send">
          </form>

        </section>

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