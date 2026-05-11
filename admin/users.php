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
if (isset($_POST['delete_user'])) {
    $id = trim($_POST['delete_user_id']);
    if (!empty($id)) {
        $stmt = $conn->prepare("DELETE FROM user WHERE user_id = ?");
        $stmt->bind_param("s", $id);
        if ($stmt->execute()) {
            $message = "User removed successfully.";
            $message_type = "info";
        } else {
            $message = "Delete failed: " . $conn->error;
            $message_type = "danger";
        }
        $stmt->close();
    }
}

$result = $conn->query("SELECT * FROM user");

include '../includes/header.php';
?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Staff Management</h2>
            <p class="text-secondary small">Manage system administrators and librarians</p>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fas fa-user-plus me-1"></i> Add New User
        </button>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?> py-2 small">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">User ID</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-primary"><?php echo htmlspecialchars($row['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td>
                                <span class="badge <?php echo ($row['role'] == 'admin') ? 'bg-danger' : 'bg-info'; ?> rounded-pill">
                                    <?php echo ucfirst(htmlspecialchars($row['role'])); ?>
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn btn-outline-secondary btn-sm border-0 p-1"
                                    onclick="openEditModal(
                                        '<?php echo htmlspecialchars($row['user_id'],    ENT_QUOTES); ?>',
                                        '<?php echo htmlspecialchars($row['first_name'], ENT_QUOTES); ?>',
                                        '<?php echo htmlspecialchars($row['last_name'],  ENT_QUOTES); ?>',
                                        '<?php echo htmlspecialchars($row['email'],      ENT_QUOTES); ?>',
                                        '<?php echo htmlspecialchars($row['username'],   ENT_QUOTES); ?>',
                                        '<?php echo htmlspecialchars($row['role'],       ENT_QUOTES); ?>'
                                    )">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button type="button" class="btn btn-outline-danger btn-sm border-0 p-1"
                                    onclick="confirmDelete('<?php echo htmlspecialchars($row['user_id'], ENT_QUOTES); ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br>
    <a href="dashboard.php" class="btn btn-secondary">
            Back to Dashboard
        </a>
</div>

<form id="deleteForm" action="" method="POST" style="display:none;">
    <input type="hidden" name="delete_user" value="1">
    <input type="hidden" name="delete_user_id" id="delete_user_id_input">
</form>
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Staff Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small">User ID (e.g. U001)</label>
                            <input type="text" name="user_id" class="form-control" placeholder="U001" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">First Name</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Last Name</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">Password (Min 8 chars)</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">Role</label>
                            <select name="role" class="form-select">
                                <option value="librarian">Librarian</option>
                                <option value="staff">Staff</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="register_user" class="btn btn-primary btn-sm">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Staff Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small">User ID</label>
                            <input type="text" id="edit_display_id" class="form-control" disabled>
                            <input type="hidden" name="edit_user_id" id="edit_user_id">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Role</label>
                            <select name="edit_role" id="edit_role" class="form-select">
                                <option value="librarian">Librarian</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">First Name</label>
                            <input type="text" name="edit_first_name" id="edit_first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Last Name</label>
                            <input type="text" name="edit_last_name" id="edit_last_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Username</label>
                            <input type="text" name="edit_username" id="edit_username" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Email Address</label>
                            <input type="email" name="edit_email" id="edit_email" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">New Password <span class="text-secondary">(leave blank to keep current)</span></label>
                            <input type="password" name="edit_password" class="form-control" placeholder="Min 8 characters">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_user" class="btn btn-primary btn-sm">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(user_id, first_name, last_name, email, username, role) {
    document.getElementById('edit_display_id').value  = user_id;
    document.getElementById('edit_user_id').value     = user_id;
    document.getElementById('edit_first_name').value  = first_name;
    document.getElementById('edit_last_name').value   = last_name;
    document.getElementById('edit_email').value       = email;
    document.getElementById('edit_username').value    = username;
    document.getElementById('edit_role').value        = role;
    new bootstrap.Modal(document.getElementById('editUserModal')).show();
}

function confirmDelete(user_id) {
    if (confirm('Are you sure you want to delete this user?')) {
        document.getElementById('delete_user_id_input').value = user_id;
        document.getElementById('deleteForm').submit();
    }
}
</script>

<?php include '../includes/footer.php'; ?>