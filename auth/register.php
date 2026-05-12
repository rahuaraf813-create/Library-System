<?php
session_start();
include '../config/db.php';

$message = '';
$message_type = '';

if (isset($_POST['register_user'])) {
    $user_id    = trim($_POST['user_id']);
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];

    if (!preg_match('/^U\d{3,}$/', $user_id)) {
        $message = "Invalid User ID! Must start with 'U' followed by 3 digits (e.g. U001).";
        $message_type = "danger";
    } elseif (strlen($password) < 8) {
        $message = "Password must be at least 8 characters.";
        $message_type = "danger";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email address format.";
        $message_type = "danger";
    } else {
        $check = $conn->prepare("SELECT user_id FROM user WHERE user_id = ? OR username = ? OR email = ?");
        $check->bind_param("sss", $user_id, $username, $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "User ID, username or email already exists.";
            $message_type = "danger";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO user (user_id, first_name, last_name, username, email, password, role, is_approved) VALUES (?, ?, ?, ?, ?, ?, 'librarian', 0)");
            $stmt->bind_param("ssssss", $user_id, $first_name, $last_name, $username, $email, $hashed);

            if ($stmt->execute()) {
                $message = "Registration successful! Please wait for admin approval before logging in.";
                $message_type = "success";
            } else {
                $message = "Registration failed. Please try again.";
                $message_type = "danger";
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library System - Register</title>
    <link rel="stylesheet" href="../assets/css/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('../bg.webp');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }
        .register-card { width: 100%; max-width: 480px; margin: auto; }
        .card { backdrop-filter: blur(6px); background: rgba(255,255,255,0.85); }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="text-center mb-1">LIB-SYS</h3>
                <p class="text-center text-secondary small mb-4">Create a Staff Account</p>

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $message_type; ?> py-2 small">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">User ID (e.g. U001)</label>
                        <input type="text" name="user_id" class="form-control" placeholder="U001" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">First Name</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Last Name</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">Password (Min 8 characters)</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" name="register_user" class="btn btn-primary w-100 py-2">Register</button>
                    <a href="login.php" class="btn btn-light w-100 mt-2 border">Back to Login</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>