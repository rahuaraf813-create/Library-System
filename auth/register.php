<?php
include'../includes/session_check.php';
include '../config/db.php';

if (isset($_POST['add_member'])) {
    $member_id = $_POST['member_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $birthday = $_POST['birthday'];
    $email = $_POST['email'];

    $check_sql = "SELECT * FROM member WHERE member_id = ? OR email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ss", $member_id, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Member ID or Email already exists.";
        $stmt->close();
    } else {
        $stmt->close();
        $insert_sql = "INSERT INTO member (member_id, first_name, last_name, birthday, email) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sssss", $member_id, $first_name, $last_name, $birthday, $email);

        if ($stmt->execute()) {
            $success = "Member registered successfully.";
        } else {
            $error = "Registration failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Member</title>
    <link rel="stylesheet" href="../assets/css/css/bootstrap.min.css">
    <style>
        body { background-color: whitesmoke; padding: 20px; }
        .member-container { max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 8px; border: 1px solid lightgrey; }
    </style>
</head>
<body>

<div class="container">
    <div class="member-container shadow-sm">
        <h3 class="mb-4">Add New Library Member</h3>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger p-2 small"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success p-2 small"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold">Member ID</label>
                <input type="text" name="member_id" class="form-control" placeholder="M001" required>
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
                <label class="form-label small fw-bold">Birthday</label>
                <input type="date" name="birthday" class="form-control" required>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <button type="submit" name="add_member" class="btn btn-primary w-100">Register Member</button>
            <a href="dashboard.php" class="btn btn-light w-100 mt-2 border">Back to Dashboard</a>
        </form>
    </div>
</div>

</body>
</html>