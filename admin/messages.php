<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLONR - Messages</title>
    <link rel="stylesheet" href="../global.css">
    <link rel="stylesheet" href="admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
   
</head>
<body>
    <header>
        <div class="container">
            <a href="admindashboard.php"><h1 class="title">CLONR</h1></a>
            <nav>
                <ul class="navbar">
                    <li><a href="products.php">Products</a></li>
                    <li><a href="order.php">Orders</a></li>
                    <li><a href="users.php">Users</a></li>
                    <li><a href="messages.php">Messages</a></li>
                    <li><a href="../AdminUpdateProfile.php">Profile</a></li>
                    <li><a href="../logout.php" class="logout-btn">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="admin-main">
        <div class="admin-container">
            <h1 class="admin-title">Customer Messages</h1>
            
            <!-- Admin Message Form -->
            <div class="message-form">
                <h2>Send Message to User</h2>
                <form action="send_admin_message.php" method="post">
                    <div class="form-group">
                        <label for="user_id">Select User:</label>
                        <select class="user-select" id="user_id" name="user_id" required>
                            <option value="">-- Select User --</option>
                            <?php
                            // Assuming you have a $users array from your database
                            foreach ($users as $user) {
                                echo '<option value="' . htmlspecialchars($user['id']) . '">' 
                                     . htmlspecialchars($user['name']) . ' (' 
                                     . htmlspecialchars($user['email']) . ')</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject:</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn-primary">Send Message</button>
                </form>
            </div>
            
            <div class="messages-list">
                <?php if (empty($messages)): ?>
                    <p class="no-messages">No messages found.</p>
                <?php else: ?>
                    <?php foreach ($messages as $message): ?>
                    <div class="message-card <?= $message['is_read'] ? 'read' : 'unread' ?> <?= $message['sender_type'] === 'admin' ? 'admin-message' : '' ?>">
                        <div class="message-header">
                            <h3><?= htmlspecialchars($message['subject']) ?></h3>
                            <span class="message-date"><?= htmlspecialchars(date('M d, Y h:i A', strtotime($message['created_at']))) ?></span>
                        </div>
                        <div class="message-sender">
                            <?php if ($message['sender_type'] === 'admin'): ?>
                                <span class="sender">Admin:</span> <?= htmlspecialchars($message['name']) ?>
                            <?php else: ?>
                                From: <?= htmlspecialchars($message['name']) ?> (<?= htmlspecialchars($message['email']) ?>)
                            <?php endif; ?>
                        </div>
                        <div class="message-content">
                            <?= nl2br(htmlspecialchars($message['message'])) ?>
                        </div>
                        <div class="message-actions">
                            <?php if ($message['sender_type'] !== 'admin'): ?>
                                <a href="mailto:<?= htmlspecialchars($message['email']) ?>" class="action-btn reply">Reply</a>
                            <?php endif; ?>
                            <a href="mark_read.php?id=<?= $message['id'] ?>" class="action-btn">Mark as <?= $message['is_read'] ? 'Unread' : 'Read' ?></a>
                            <a href="delete_message.php?id=<?= $message['id'] ?>" class="action-btn delete" onclick="return confirm('Are you sure you want to delete this message?')">Delete</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-content">
            <h2>CLONR - Wear the Movement</h2>
            <p>Email: noreply.CLONR@gmail.com | Phone: +63 XXX XXX XXXX</p>
            <p>© 2025 CLONR. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>