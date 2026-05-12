<?php 
session_start();
include '../config/db.php';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $query = "SELECT * FROM user WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            if ($user['is_approved'] == 0) {
                $error = "Your account is pending admin approval.";
            } else {
                 $_SESSION['user_id']   = $user['user_id'];
                 $_SESSION['username']  = $user['username'];
                 $_SESSION['role']      = $user['role'];
                 $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];
                 header("Location: ../admin/dashboard.php");
                 exit();
                }
        } else {
        $error = "Invalid username or password!";
        }
    } else {
    $error = "Invalid username or password!";
    }
$stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library System - Login</title>
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
    .login-card { width: 100%; max-width: 400px; padding: 15px; margin: auto; }
    .card { 
        -webkit-backdrop-filter: blur(6px); 
        backdrop-filter: blur(6px); 
        background: rgba(255,255,255,0.85); 
    }
</style>
</head>
<body>
    <div class="login-card">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="text-center mb-4">LIBRARY-SYSTEM <br> Login</h3>
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger py-2 small"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label small font-weight-bold">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small font-weight-bold">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary w-100 py-2">Sign In</button>
                    <hr class="my-3 opacity-25">

                    <p class="text-center small text-secondary mb-0">
                        Don't have an account? <a href="register.php">Register here</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>