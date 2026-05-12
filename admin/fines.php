<?php
include '../includes/session_check.php';
include '../config/db.php';

$message = '';
$message_type = '';


if (isset($_POST['add_fine'])) {
    $fine_id = trim($_POST['fine_id']);
    $book_id = trim($_POST['book_id']);
    $member_id = trim($_POST['member_id']);
    $fine_amount = $_POST['fine_amount'];

    try {
        $stmt = $conn->prepare("INSERT INTO fine (fine_id, book_id, member_id, fine_amount, fine_date_modified) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssd", $fine_id, $book_id, $member_id, $fine_amount);
        if ($stmt->execute()) {
            $message = "Fine issued successfully.";
            $message_type = "success";
        }
    } catch (mysqli_sql_exception $e) {
        $message = "Error: Invalid IDs provided.";
        $message_type = "danger";
    }
}


if (isset($_POST['delete_fine'])) {
    $id = $_POST['delete_fine_id'];
    $stmt = $conn->prepare("DELETE FROM fine WHERE fine_id = ?");
    $stmt->bind_param("s", $id);
    if ($stmt->execute()) {
        $message = "Fine record deleted.";
        $message_type = "info";
    }
}


$query = "SELECT fine.*, book.book_name, member.first_name 
          FROM fine 
          JOIN book ON fine.book_id = book.book_id 
          JOIN member ON fine.member_id = member.member_id";
$result = $conn->query($query);

include '../includes/header.php';
?>
<div class="container py-4">
    <div class="d-flex justify-content-between mb-4">
        <h2>Fine Management</h2>
        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#fineModal">Issue Fine</button>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-<?= $message_type ?> alert-dismissible fade show small" role="alert">
            <?= $message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
     

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fine ID</th>
                        <th>Book</th>
                        <th>Member</th>
                        <th>Amount</th>
                        <th>Modified</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['fine_id']) ?></td>
                        <td><?= htmlspecialchars($row['book_name']) ?></td>
                        <td><?= htmlspecialchars($row['first_name']) ?></td>
                        <td>LKR <?= number_format($row['fine_amount'], 2) ?></td>
                        <td><?= $row['fine_date_modified'] ?></td>
                        <td>
                            <form method="POST" onsubmit="return confirm('Delete this fine?');">
                           <input type="hidden" name="delete_fine_id" value="<?= htmlspecialchars($row['fine_id']) ?>">
                          <button type="submit" name="delete_fine" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                          </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
     <br>
    <a href="dashboard.php" class="btn btn-secondary">
            Back to Dashboard
        </a>
</div>

</div> 

    <div class="modal fade" id="fineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Issue New Fine</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Fine ID</label>
                    <input type="text" name="fine_id" class="form-control" placeholder="e.g. F001" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Book ID</label>
                    <input type="text" name="book_id" class="form-control" placeholder="e.g. B001" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Member ID</label>
                    <input type="text" name="member_id" class="form-control" placeholder="e.g. M001" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Fine Amount (LKR)</label>
                    <input type="number" name="fine_amount" class="form-control" step="0.01" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="add_fine" class="btn btn-danger">Save Fine</button>
            </div>
        </form>
    </div>
</div>
        </div>

<?php include '../includes/footer.php'; ?>