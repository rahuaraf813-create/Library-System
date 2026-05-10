<?php
include '../includes/session_check.php';
include '../config/db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

$message = '';
$message_type = '';
if (isset($_POST['register_user'])) {
    $user_id     = trim($_POST['user_id']);
    $first_name  = trim($_POST['first_name']);
    $last_name   = trim($_POST['last_name']);
    $email       = trim($_POST['email']);
    $username    = trim($_POST['username']);
    $password    = $_POST['password'];
    $role        = $_POST['role'];
    $is_approved = 1;

    if (!preg_match('/^U\d{3,}$/', $user_id)) {
        $message = "Invalid ID! Must start with 'U' followed by 3 digits (e.g. U001).";
        $message_type = "danger";
    } elseif (strlen($password) < 8) {
        $message = "Password must be at least 8 characters.";
        $message_type = "danger";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email address format.";
        $message_type = "danger";
    } else {
        $check = $conn->prepare("SELECT user_id FROM user WHERE username = ? OR email = ? OR user_id = ?");
        $check->bind_param("sss", $username, $email, $user_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "User ID, username, or email already exists.";
            $message_type = "danger";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare(
                "INSERT INTO user (user_id, email, first_name, last_name, username, password, role, is_approved)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("sssssssi", $user_id, $email, $first_name, $last_name, $username, $hashed, $role, $is_approved);

            if ($stmt->execute()) {
                $message = "User registered successfully!";
                $message_type = "success";
            } else {
                $message = "Insert failed: " . $conn->error;
                $message_type = "danger";
            }
            $stmt->close();
        }
        $check->close();
    }
}
if (isset($_POST['update_user'])) {
    $edit_user_id = trim($_POST['edit_user_id']);
    $first_name   = trim($_POST['edit_first_name']);
    $last_name    = trim($_POST['edit_last_name']);
    $email        = trim($_POST['edit_email']);
    $username     = trim($_POST['edit_username']);
    $role         = trim($_POST['edit_role']);
    $new_password = $_POST['edit_password'];

    if (empty($edit_user_id)) {
        $message = "Error: User ID missing.";
        $message_type = "danger";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email address format.";
        $message_type = "danger";
    } else {
        $check = $conn->prepare("SELECT user_id FROM user WHERE (username = ? OR email = ?) AND user_id != ?");
        $check->bind_param("sss", $username, $email, $edit_user_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "Username or email already taken by another user.";
            $message_type = "danger";
        } else {
            if (!empty($new_password)) {
                if (strlen($new_password) < 8) {
                    $message = "Password must be at least 8 characters.";
                    $message_type = "danger";
                } else {
                    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare(
                        "UPDATE user SET first_name=?, last_name=?, email=?, username=?, role=?, password=?
                         WHERE user_id=?"
                    );
                    $stmt->bind_param("sssssss", $first_name, $last_name, $email, $username, $role, $hashed, $edit_user_id);
                }
            } else {
                $stmt = $conn->prepare(
                    "UPDATE user SET first_name=?, last_name=?, email=?, username=?, role=?
                     WHERE user_id=?"
                );
                $stmt->bind_param("ssssss", $first_name, $last_name, $email, $username, $role, $edit_user_id);
            }

            if (empty($message)) {
                if ($stmt->execute()) {
                    $message = "User updated successfully!";
                    $message_type = "success";
                } else {
                    $message = "Update failed: " . $conn->error;
                    $message_type = "danger";
                }
                $stmt->close();
            }
        }
        $check->close();
    }
}