<?php
include '../includes/session_check.php';
include '../config/db.php';


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
                            
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>