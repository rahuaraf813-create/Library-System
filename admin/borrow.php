<?php
include '../includes/session_check.php';
include '../config/db.php';
include '../includes/header.php';

$message = '';
$message_type = '';

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

?>




<div class="container py-4">
    <div class="d-flex justify-content-between mb-4">
        <h2>Borrow Management</h2>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">Add Borrow</button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Borrow ID</th>
                        <th>Book</th>
                        <th>Member</th>
                        <th>Status</th>
                        <th>Date Modified</th>
                        
                    </tr>
                </thead>
                <tbody>
                    </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5>New Borrow Record</h5></div>
            <div class="modal-body">
                </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>