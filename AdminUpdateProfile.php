<?php
    @include 'config.php';
    session_start();

if (!isset($_SESSION['admin_id'])) {
    header('location:login.php');
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Get admin info
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$admin_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Validate admin user
if (!$user || $user['user_type'] !== 'admin') {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}

// Handle form submission
if (isset($_POST['update_profile'])) {
    // Update name and email
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if ($name !== $user['name'] || $email !== $user['email']) {
        $update_profile = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $update_profile->execute([$name, $email, $admin_id]);
    }

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $image_ext = pathinfo($image, PATHINFO_EXTENSION);
        $image_name = uniqid() . '.' . $image_ext;
        $image_path = 'uploaded_img/' . $image_name;

        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($image_ext, $allowed_ext)) {
            $message[] = "Invalid image type!";
        } elseif ($image_size > 2000000) {
            $message[] = "Image is too large!";
        } else {
            move_uploaded_file($image_tmp, $image_path);

            if (!empty($user['image']) && file_exists('uploaded_img/' . $user['image'])) {
                unlink('uploaded_img/' . $user['image']);
            }

            $update_img = $conn->prepare("UPDATE users SET image = ? WHERE id = ?");
            $update_img->execute([$image_name, $admin_id]);
        }
    }

    // Handle password change
    if (!empty($_POST['old_pass']) && !empty($_POST['new_pass']) && !empty($_POST['confirm_pass'])) {
        $old_pass = md5($_POST['old_pass']);
        $new_pass = md5($_POST['new_pass']);
        $confirm_pass = md5($_POST['confirm_pass']);

        if ($user['password'] !== $old_pass) {
            $message[] = "Old password is incorrect!";
        } elseif ($new_pass !== $confirm_pass) {
            $message[] = "New passwords do not match!";
        } else {
            $update_pass = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update_pass->execute([$new_pass, $admin_id]);
            $message[] = "Password updated!";
        }
    }

    // Refresh user info
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$admin_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Admin Profile</title>
    <link rel="stylesheet" href="global.css"/>
    <link rel="stylesheet" href="profile.css"/>
     <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
   
</head>
<body>

    <header>
        <div class="update">
        <a href="admin/admindashboard.php"><h1 class="title">CLONR</h1></a>
            <nav>
              <ul class="navbar">
                <li><a href="admin/products.php">Products</a></li>
                <li><a href="admin/order.php">Orders</a></li>
                <li><a href="admin/users.php">Users</a></li>
                <li><a href="admin/messages.php">Messages</a></li>
                
               <li><a href="AdminUpdateProfile.php" class="logout-btn">Profile</a></li>
               <li><a href="logout.php" class="logout-btn">Logout</a></li>
              </ul>
            </nav>
            
        </div>
    </header>
    <hr class="custom-hr">
    <?php if (!empty($message)): ?>
    <div class="messages">
        <?php foreach ($message as $msg): ?>
            <p><?= htmlspecialchars($msg); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

    <div class="update-container">
        <div class="update-header">
            <h2>Welcome, <?= htmlspecialchars($user['name']); ?></h2>
        </div>

        <div class="update-content">
            <!-- Profile Image -->
            <div class="update-image">
                <img src="uploaded_img/<?= htmlspecialchars($user['image'] ?: 'default_profile.png'); ?>" alt="Profile Image">
                <?php if ($user['user_type'] === 'admin'): ?>

            <?php endif; ?>
            </div>

            <!-- User Details -->
            <div class="update-info">
                <h3>User Information</h3>
                <form action="AdminUpdateProfile.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="update_profile" value="1">

                <div class="form-group">
                <label for="image">Update Profile Image:</label>
                <input type="file" name="image" accept="image/*">
                </div>


                <div class="form-group">
                    <label for="fullName">Name:</label>
                    <input type="text" id="fullName" name="name" value="<?= htmlspecialchars($user['name'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="user_type">User Type:</label>
                    <input type="text" id="user_type" name="user_type" value="<?= htmlspecialchars($user['user_type']); ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="old_pass">Old Password:</label>
                    <input type="password" name="old_pass" placeholder="Enter previous password">
                </div>
                <div class="form-group">
                    <label for="new_pass">New Password:</label>
                    <input type="password" name="new_pass" placeholder="Enter new password">
                </div>
                <div class="form-group">
                    <label for="confirm_pass">Confirm Password:</label>
                    <input type="password" name="confirm_pass" placeholder="Confirm new password">
                </div>

                <div class="form-group button-container">
                    <a href="logout.php" class="delete-btn" onclick="return confirm('Are you sure you want to log out?')">Logout</a>
                    <button type="submit" class="save-btn">Save Changes</button>
                </div>
                   
                    </div>
                </form>
            </div>
        </div>
    </div>
    

    <hr class="custom-hr">

    <footer>
        <div class="footer-content">
            <h2>CLONR - Wear the Movement</h2>
            <p>Email: customerservice.CLONR@gmail.com | Phone: +63 XXX XXX XXXX</p>
            <p>© 2025 CLONR. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
