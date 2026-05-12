<?php
include '../includes/session_check.php';
include '../config/db.php';

$msg = '';
$msg_type = '';


if (isset($_POST['add_borrow'])) {
    $borrow_id = trim($_POST['borrow_id']);
    $book_id = trim($_POST['book_id']);
    $member_id = trim($_POST['member_id']);
    $borrow_status = $_POST['borrow_status'];

    $check = $conn->prepare("SELECT borrow_id FROM bookborrower WHERE borrow_id = ?");
    $check->bind_param("s", $borrow_id);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $message = "Borrow ID already exists!";
        $message_type = "danger";
    } else {
        $stmt = $conn->prepare("INSERT INTO bookborrower (borrow_id, book_id, member_id, borrow_status, borrower_date_modified) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $borrow_id, $book_id, $member_id, $borrow_status); 
        
        if ($stmt->execute()) {
            $message = "Borrow record added!";
            $message_type = "success";
        } else {
            $message = "Error: " . $conn->error;
            $message_type = "danger";
        }
    }

}   
    $query = "SELECT bb.*, b.book_name, m.first_name 
          FROM bookborrower bb
          JOIN book b ON bb.book_id = b.book_id 
          JOIN member m ON bb.member_id = m.member_id";
$borrow_data = $conn->query($query);

include '../includes/header.php';
?>




<?php if ($message): ?>
    <div class="alert alert-<?= $message_type ?> alert-dismissible fade show" role="alert">
        <?= $message ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header"><h5 class="modal-title">New Record</h5></div>
            <div class="modal-body">
                <input type="text" name="borrow_id" class="form-control mb-2" placeholder="ID" required>
                <input type="text" name="book_id" class="form-control mb-2" placeholder="Book ID" required>
                <input type="text" name="member_id" class="form-control mb-2" placeholder="Member ID" required>
                <select name="borrow_status" class="form-select">
                    <option value="borrowed">Borrowed</option>
                    <option value="returned">Returned</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="submit" name="add_borrow" class="btn btn-primary">Save Entry</button>
            </div>
        </form>
    </div>
</div>



<div class="container py-4">
    <div class="d-flex justify-content-between mb-4">
        <h2 class="h4">Borrow Management</h2>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">Add Borrow</button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Book</th>
                        <th>Member</th>
                        <th>Status</th>
                        <th>Last Modified</th>
                        <th>Actions</th>                        
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $borrow_data->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['borrow_id']) ?></td>
                        <td><?= htmlspecialchars($row['book_name']) ?></td>
                        <td><?= htmlspecialchars($row['first_name']) ?></td>
                        <td>
                            <span class="badge bg-<?= $row['borrow_status'] == 'borrowed' ? 'warning' : 'success' ?>">
                                <?= ucfirst($row['borrow_status']) ?>
                            </span>
                        </td>
                        <td><?= $row['borrower_date_modified'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>