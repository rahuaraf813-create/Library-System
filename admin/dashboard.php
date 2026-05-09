<?php
//include '../includes/session_check.php';
include '../config/db.php';
include '../includes/header.php';

//taking details from database
$book_count = $conn->query("SELECT book_id FROM book")->num_rows;
$member_count = $conn->query("SELECT member_id FROM member")->num_rows;
$borrow_count = $conn->query("SELECT borrow_id FROM bookborrower WHERE borrow_status='borrowed'")->num_rows;
?>

<div class="container pb-5">
    <div class="row mb-4">
        <div class="col">
            <h2 class="display-6 font-weight-bold">Administrative Dashboard</h2>
            <p class="text-muted">Library Management System Overview</p>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body text-center">
                    <h6 class="text-uppercase text-muted small">Total Books</h6>
                    <h2 class="display-6"><?php echo $book_count; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body text-center">
                    <h6 class="text-uppercase text-muted small">Active Members</h6>
                    <h2 class="display-6"><?php echo $member_count; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body text-center">
                    <h6 class="text-uppercase text-muted small">Books Issued</h6>
                    <h2 class="display-6"><?php echo $borrow_count; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body text-center">
                    <h6 class="text-uppercase small">System Status</h6>
                    <h2 class="h4 mt-2">Operational</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Management Modules</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-2">
                            <a href="books.php" class="btn btn-outline-dark btn-block p-3">Manage Books</a>
                        </div>
                        <div class="col-md-2">
                            <a href="members.php" class="btn btn-outline-dark btn-block p-3">Members</a>
                        </div>
                        <div class="col-md-2">
                            <a href="borrow.php" class="btn btn-outline-dark btn-block p-3">Issues</a>
                        </div>
                        <div class="col-md-2">
                            <a href="categories.php" class="btn btn-outline-dark btn-block p-3">Categories</a>
                        </div>
                        <div class="col-md-2">
                            <a href="fines.php" class="btn btn-outline-dark btn-block p-3">Fines</a>
                        </div>
                        <div class="col-md-2">
                            <a href="../auth/logout.php" class="btn btn-outline-danger btn-block p-3">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>