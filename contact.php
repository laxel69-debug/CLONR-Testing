<?php
@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

// Fetch user details
$user_info = [];
$select_user = $conn->prepare("SELECT name, email FROM `users` WHERE id = ?");
$select_user->execute([$user_id]);
$user_info = $select_user->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['send'])) {
    $subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
    $msg = filter_var($_POST['msg'], FILTER_SANITIZE_STRING);

    // Fetch all admins
    $adminQuery = $conn->query("SELECT id FROM users WHERE user_type = 'admin'");
    $admins = $adminQuery->fetchAll(PDO::FETCH_ASSOC);

    $duplicateFound = false;

    foreach ($admins as $admin) {
        $admin_id = $admin['id'];

        // Check for duplicate message for each admin
        $checkDuplicate = $conn->prepare("SELECT * FROM messages WHERE user_id = ? AND sender_id = ? AND message = ?");
        $checkDuplicate->execute([$admin_id, $user_id, $msg]);

        if ($checkDuplicate->rowCount() > 0) {
            $duplicateFound = true;
            continue;
        }

        // Send message to this admin
        $insert = $conn->prepare("INSERT INTO messages (user_id, sender_id, sender_type, subject, message, is_read, created_at)
                                  VALUES (?, ?, 'user', ?, ?, 0, NOW())");
        $insert->execute([$admin_id, $user_id, $subject, $msg]);
    }

    if ($duplicateFound) {
        $message[] = 'Some messages were not sent because you already sent the same message!';
    } else {
        $message[] = 'Message sent successfully to all admins!';
    }
}

// Fetch message history with admin names
$message_history = [];
$select_history = $conn->prepare("
    SELECT m.*, 
           sender.name as sender_name,
           receiver.name as receiver_name 
    FROM `messages` m
    LEFT JOIN users sender ON m.sender_id = sender.id
    LEFT JOIN users receiver ON m.user_id = receiver.id
    WHERE (m.sender_id = ? AND m.sender_type = 'user')
       OR (m.user_id = ? AND m.sender_type = 'admin')
    ORDER BY m.created_at DESC
    LIMIT 5
");
$select_history->execute([$user_id, $user_id]);
$message_history = $select_history->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>CLONR - Contact Us</title>
        <link rel="stylesheet" href="global.css"/>
        <link rel="stylesheet" href="contact.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
        <style>
            .message-history {
                margin: 2rem auto;
                max-width: 800px;
            }
            .history-item {
                background: #f9f9f9;
                padding: 1rem;
                margin-bottom: 1rem;
                border-radius: 5px;
                position: relative;
            }
            .outgoing {
                border-left: 4px solid #4CAF50;
            }
            .incoming {
                border-left: 4px solid #2196F3;
            }
            .history-header {
                display: flex;
                justify-content: space-between;
                margin-bottom: 0.5rem;
            }
            .history-participants {
                font-weight: 500;
                margin-bottom: 0.5rem;
            }
            .history-subject {
                font-weight: bold;
                color: #333;
                margin-bottom: 0.5rem;
            }
            .history-date {
                color: #666;
                font-size: 0.9rem;
            }
            .history-message {
                color: #555;
                padding: 0.5rem;
                background: white;
                border-radius: 4px;
            }
            .contact-form {
                max-width: 800px;
                margin: 0 auto;
            }
            .message-direction {
                font-size: 0.9rem;
                color: #666;
                margin-bottom: 0.5rem;
            }
        </style>
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
          
        <section class="contact">
          <h1 class="title">Contact Us</h1>

          <?php
            if (isset($message)) {
                foreach ($message as $msg) {
                    echo "<div class='message'>$msg</div>";
                }
            }
          ?>

          <div class="message-history">
              <h2>Recent Messages</h2>
              <?php if(empty($message_history)): ?>
                  <p>No recent messages</p>
              <?php else: ?>
                  <?php foreach($message_history as $msg): ?>
                  <div class="history-item <?= $msg['sender_type'] === 'user' ? 'outgoing' : 'incoming' ?>">
                      <div class="message-direction">
                          <?php if($msg['sender_type'] === 'user'): ?>
                              <span>You → <?= htmlspecialchars($msg['receiver_name']) ?> (Admin)</span>
                          <?php else: ?>
                              <span><?= htmlspecialchars($msg['sender_name']) ?> (Admin) → You</span>
                          <?php endif; ?>
                      </div>
                      <div class="history-header">
                          <span class="history-subject"><?= htmlspecialchars($msg['subject']) ?></span>
                          <span class="history-date"><?= date('M d, Y h:i A', strtotime($msg['created_at'])) ?></span>
                      </div>
                      <div class="history-message">
                          <?= nl2br(htmlspecialchars($msg['message'])) ?>
                      </div>
                  </div>
                  <?php endforeach; ?>
              <?php endif; ?>
          </div>

          <form action="" method="POST" class="contact-form">
              <input type="hidden" name="name" value="<?= htmlspecialchars($user_info['name'] ?? '') ?>">
              <input type="hidden" name="email" value="<?= htmlspecialchars($user_info['email'] ?? '') ?>">
              
              <div class="form-group">
                  <label for="subject">Subject:</label>
                  <input type="text" id="subject" name="subject" class="box" required placeholder="Message subject">
              </div>
              
              <div class="form-group">
                  <label for="msg">Your Message:</label>
                  <textarea id="msg" name="msg" class="box" required placeholder="Enter your message" cols="30" rows="10"></textarea>
              </div>
              
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