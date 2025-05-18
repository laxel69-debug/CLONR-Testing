<?php
@include '../../config.php';
session_start();

if(!isset($_SESSION['user_id'])){
   header('location:login.php');
   exit;
}

// Get the most recent order for this user
$order_query = $conn->prepare("SELECT id FROM orders WHERE user_id = ? ORDER BY placed_on DESC LIMIT 1");
$order_query->execute([$_SESSION['user_id']]);
$order = $order_query->fetch(PDO::FETCH_ASSOC);

if(!$order){
   header('location:orders.php');
   exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Order Confirmation - CLONR</title>
   <script src="https://cdn.tailwindcss.com"></script>
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
   <style>
      @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
      body {
         font-family: 'Poppins', sans-serif;
         background: #f9f7f6;
      }
   </style>
</head>
<body class="min-h-screen flex flex-col">
   <header class="bg-gray-900">
      <div class="container mx-auto flex items-center px-8 py-6">
         <a href="../../main.php" class="text-white text-3xl font-light mr-4">
            <i class="fas fa-arrow-left"></i>
         </a>
         <h1 class="flex-grow text-center text-white font-semibold text-3xl select-none tracking-wide">Order Confirmation</h1>
         <div class="w-12"></div>
      </div>
   </header>

   <main class="flex-grow flex items-center justify-center p-6">
      <div class="w-full max-w-3xl bg-white rounded-3xl shadow-xl overflow-hidden">
         <section class="bg-gray-100 flex flex-col items-center gap-6 py-12 px-12 text-center">
            <i class="fas fa-check-circle text-green-500 text-6xl"></i>
            <h2 class="text-gray-900 font-bold text-4xl tracking-wide select-none">Order Placed Successfully!</h2>
            <p class="text-gray-600 text-lg">Thank you for your purchase. Your order has been confirmed.</p>
         </section>

         <section class="px-10 py-8 text-center">
            <p class="mb-6">Your order number is: <strong>#<?= $order['id'] ?></strong></p>
            <p class="mb-8">We've sent a confirmation email with your receipt.</p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
               <a href="../../main.php" class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition">
                  Continue Shopping
               </a>
               <a href="receipt.php?order_id=<?= $order['id'] ?>" class="bg-gray-900 text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition">
                  View Receipt
               </a>
            </div>
         </section>
      </div>
   </main>

   <footer class="bg-gray-900 text-white py-8">
      <div class="container mx-auto text-center">
         <h2 class="text-2xl font-bold mb-2">CLONR - Wear the Movement</h2>
         <p class="mb-1">Email: noreply.CLONR@gmail.com | Phone: +63 XXX XXX XXXX</p>
         <p>© 2025 CLONR. All Rights Reserved.</p>
      </div>
   </footer>
</body>
</html>