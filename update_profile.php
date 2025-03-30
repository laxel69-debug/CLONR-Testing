<?php
@include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$get_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$get_user->execute([$user_id]);
$user = $get_user->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found");
}

if (isset($_POST['update_profile'])) {
    $name = !empty($_POST['name']) ? filter_var($_POST['name'], FILTER_SANITIZE_STRING) : $user['name'];
    $email = !empty($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : $user['email'];

    // Update only if values are changed
    if ($name !== $user['name'] || $email !== $user['email']) {
        $update_profile = $conn->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
        $update_profile->execute([$name, $email, $user_id]);
    }

    // Handle image upload safely
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_ext = pathinfo($image, PATHINFO_EXTENSION);
        $image_new_name = uniqid() . '.' . $image_ext;
        $image_folder = 'uploaded_img/' . $image_new_name;

        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($image_ext, $allowed_types)) {
            $message[] = "Invalid image type. Allowed: JPG, JPEG, PNG, GIF.";
        } elseif ($image_size > 2000000) {
            $message[] = "Image size is too large!";
        } else {
            move_uploaded_file($image_tmp_name, $image_folder);

            // Delete the old image if it exists
            if (!empty($user['image']) && file_exists('uploaded_img/' . $user['image'])) {
                unlink('uploaded_img/' . $user['image']);
            }

            $update_image = $conn->prepare("UPDATE `users` SET image = ? WHERE id = ?");
            $update_image->execute([$image_new_name, $user_id]);
            $message[] = "Image updated successfully!";
        }
    }
        // Password update logic (FIXED)
        if (!empty($_POST['old_pass']) && !empty($_POST['new_pass']) && !empty($_POST['confirm_pass'])) {
            $old_pass = md5($_POST['old_pass']);  // Hash old password for comparison
            $new_pass = md5($_POST['new_pass']);
            $confirm_pass = md5($_POST['confirm_pass']);

            // Fetch stored password
            $check_pass = $conn->prepare("SELECT password FROM `users` WHERE id = ?");
            $check_pass->execute([$user_id]);
            $fetch_pass = $check_pass->fetch(PDO::FETCH_ASSOC);

            if (!$fetch_pass || $fetch_pass['password'] != $old_pass) {
                $message[] = 'Old password is incorrect!';
            } elseif ($new_pass != $confirm_pass) {
                $message[] = 'New password and confirm password do not match!';
            } else {
                // Update password with the correct user ID
                $update_pass_query = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
                $update_pass_query->execute([$new_pass, $user_id]);
                $message[] = 'Password updated successfully!';
            }
        }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update Profile</title>
        <link rel="stylesheet" href="global.css"/>
        <link rel="stylesheet" href="profile.css"/>
    </head>

    <body>
        <header>
            <div class="update">
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
            </div>
        </header>

        <div class="update-container">
            <div class="update-header">
                <h2>Welcome, <?= htmlspecialchars($user['name']); ?></h2>
            </div>

            <div class="update-content">
                <!-- Profile Image on the Left -->
                <div class="update-image">
                    <img src="uploaded_img/<?= htmlspecialchars($user['image'] ?: 'default_profile.png'); ?>" alt="Profile Image">

                    <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                        <input type="file" name="image" accept="image/*">
                        <button type="submit" name="update_profile" class="upload-btn">Upload</button>
                    </form>
                </div>

                <!-- User Details on the Right -->
                <div class="update-info">
                    <h3>User Information</h3>
                    <form action="update_profile.php" method="POST" enctype="multipart/form-data">
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
                            <button type="submit" class="save-btn" name="update_profile">Save Changes</button>
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
