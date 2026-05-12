<?php
include '../includes/session_check.php';
include '../config/db.php';

$query = "SELECT bb.*, b.book_name, m.first_name 
          FROM bookborrower bb
          JOIN book b ON bb.book_id = b.book_id 
          JOIN member m ON bb.member_id = m.member_id";
$borrow_data = $conn->query($query);

include '../includes/header.php';
?>

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