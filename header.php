<?php
// Start session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

@include 'config.php';

// Handle messages
if(isset($message)){
   foreach($message as $msg){
      echo '
      <div class="message">
         <span>'.htmlspecialchars($msg).'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}

// Check if the user is logged in
$user_id = $_SESSION['user_id'] ?? null;
$user_data = null;

if ($user_id) {
    $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
    $select_profile->execute([$user_id]);
    $user_data = $select_profile->fetch(PDO::FETCH_ASSOC);
}

$base_url = '/clonr/';

?>
<link rel="stylesheet" href="<?= $base_url; ?>global.css">

<header class="header">
   <div class="flex">
      <!-- Profile Wrapper -->
      <div class="profile-wrapper">
            <!-- Logo Icon -->
            <div class="profile-icon" style="background-image: 
            url('<?= $base_url; ?>uploaded_img/<?= htmlspecialchars($user_data['image'] ?? 'default.png'); ?>'); 
            background-size: cover; background-position: center; border-radius: 50%; width: 50px; height: 50px;">
            </div> 
            <div class="profile">
    <?php if ($user_data): ?>
        <img src="<?= $base_url; ?>uploaded_img/<?= htmlspecialchars($user_data['image'] ?? 'default.png'); ?>" alt="Profile Image">
        <p><?= htmlspecialchars($user_data['name'] ?? 'Guest'); ?></p>
        <a href="<?= $base_url; ?>update_profile.php" class="btn">update profile</a>
       <a href="<?= $base_url; ?>products/payment/cart.php" class="btn">
        <i class="bi bi-cart"></i> </a>
        <a href="<?= $base_url; ?>history.php" class="btn" title="Order History">
            <i class="bi bi-clock-history"></i> 
        </a>
       

        <a href="<?= $base_url; ?>logout.php" class="delete-btn" onclick="return confirm('Are you sure you want to log out?')">logout</a>
    
        <?php else: ?>
        <p>No user found. <a href="<?= $base_url; ?>login.php">Login</a></p>
    <?php endif; ?>
    </div>
</div>
</header>







