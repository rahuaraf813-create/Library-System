<?php
include '../includes/session_check.php';
include '../config/db.php';

$result = $conn->query("SELECT * FROM member");

include '../includes/header.php';
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Library Members</h2>
            <p class="text-secondary small">Manage registered library members</p>
        </div>
        <a href="add_member.php" class="btn btn-primary btn-sm">
            <i class="fas fa-user-plus me-1"></i> Add New Member
        </a>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-<?php echo htmlspecialchars($_GET['type']); ?> py-2 small">
            <?php echo htmlspecialchars($_GET['msg']); ?>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Member ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Birthday</th>
                            <th>Email</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-primary"><?php echo htmlspecialchars($row['member_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['birthday']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td class="text-end pe-4">
                                <a href="edit_member.php?id=<?php echo urlencode($row['member_id']); ?>"
                                   class="btn btn-outline-secondary btn-sm border-0 p-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete_member.php?id=<?php echo urlencode($row['member_id']); ?>"
                                   class="btn btn-outline-danger btn-sm border-0 p-1"
                                   onclick="return confirm('Are you sure you want to delete this member?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>