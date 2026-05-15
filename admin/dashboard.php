<?php
include '../includes/session_check.php';
include '../config/db.php';
include '../includes/header.php';

$role = $_SESSION['role'];
//taking details from database
$book_count = $conn->query("SELECT COUNT(*) as total FROM book")->fetch_assoc()['total'];
$member_count = $conn->query("SELECT COUNT(*) as total FROM member")->fetch_assoc()['total'];
$category_count = $conn->query("SELECT COUNT(*) as total FROM bookcategory")->fetch_assoc()['total'];
$user_count   = $conn->query("SELECT COUNT(*) as total FROM user")->fetch_assoc()['total'];

$active_borrows = $conn->query("SELECT COUNT(*) as total FROM bookborrower WHERE borrow_status='borrowed'")->fetch_assoc()['total'];
$total_fines_result = $conn->query("SELECT SUM(fine_amount) as total FROM fine")->fetch_assoc()['total'];
$total_fines = $total_fines_result ? $total_fines_result : 0;

$available_books = $book_count - $active_borrows;
?>

<div class="container-fluid mt-4">
    <div class="p-6 mb-4 bg-body-tertiary rounded-3 shadow-sm border border-secondary border-opacity-25">
        <div class="row align-items-center">
            <div class="col-md-7 border-end border-secondary border-opacity-25">
                <h1 class="display-7 fw-bold text-body">&nbsp; Welcome, <?= ucfirst($role); ?>!</h1>
                <p class="fs-5 text-muted mb-0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Library Management System</p>
                <p class="small text-secondary ms-2 mt-2"><br><i class="fas fa-calendar-day me-2"></i> <?=date('l, jS F Y'); ?></p>
            </div>
            <div class="col-md-5 ps-md-5">
                <div class="row g-3">
                    <div class="col-6">
                        <small class="text-secondary d-block">Total Books</small>
                        <span class="h5 fw-bold text-body"><?= $book_count; ?></span>
                    </div>
                    <div class="col-6">
                        <small class="text-secondary d-block">Categories</small>
                        <span class="h5 fw-bold text-body"><?= $category_count; ?></span>
                    </div>
                    <div class="col-6">
                        <small class="text-secondary d-block">Members</small>
                        <span class="h5 fw-bold text-body"><?= $member_count; ?></span>
                    </div>
                    <div class="col-6">
                        <small class="text-secondary d-block">System Status</small>
                        <span class="badge bg-success">Online</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100 nav-card shadow-sm border-0">
            <div class="card-body text-center p-4">
                <div class="mb-3"><i class="fas fa-book fa-3x text-success"></i></div>
                <h5>Inventory</h5>
                <p class="small text-secondary">Manage Book Collection</p>
                <a href="books.php" class="btn btn-success btn-sm w-100">Manage Books</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 nav-card shadow-sm border-0">
            <div class="card-body text-center p-4">
                <div class="mb-3"><i class="fas fa-tags fa-3x text-info"></i></div>
                <h5>Categories</h5>
                <p class="small text-secondary">Book Classifications</p>
                <a href="categories.php" class="btn btn-info btn-sm w-100 text-white">Manage Categories</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 nav-card shadow-sm border-0">
            <div class="card-body text-center p-4">
                <div class="mb-3"><i class="fas fa-users fa-3x text-warning"></i></div>
                <h5>Members</h5>
                <p class="small text-secondary">Member Registry</p>
                <a href="members.php" class="btn btn-warning btn-sm w-100">Registry</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 nav-card shadow-sm border-0">
            <div class="card-body text-center p-4">
                <div class="mb-3"><i class="fas fa-hand-holding fa-3x text-primary"></i></div>
                <h5>Borrowing</h5>
                <p class="small text-secondary">Issue & Return Books</p>
                <a href="borrow.php" class="btn btn-primary btn-sm w-100">Circulation</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 nav-card shadow-sm border-0">
            <div class="card-body text-center p-4">
                <div class="mb-3"><i class="fas fa-file-invoice-dollar fa-3x text-danger"></i></div>
                <h5>Fines & Issues</h5>
                <p class="small text-secondary">Fines Management</p>
                <a href="fines.php" class="btn btn-danger btn-sm w-100">Open Desk</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 nav-card shadow-sm border-0">
            <div class="card-body text-center p-4">
                <div class="mb-3"><i class="fas fa-user-shield fa-3x text-secondary"></i></div>
                <h5>Staff Control</h5>
                <p class="small text-secondary">Manage System Users</p>
                 <?php if ($role === 'admin'): ?>
                <a href="users.php" class="btn btn-secondary btn-sm w-100">Open Portal</a>
                <?php else: ?>
    <button class="btn btn-secondary btn-sm w-100" onclick="alert('Access Denied. Admin only.')">Open Portal</button>
<?php endif; ?>
            </div>
        </div>
    </div>
</div>

    <hr class="my-5 opacity-25">

    <h4 class="mb-4 text-body fw-bold"><i class="fas fa-chart-line me-2"></i>Library Insights</h4>
    <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
        <div class="col">
            <div class="card h-100 shadow-sm border-0 bg-body-tertiary">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 p-3 bg-primary bg-opacity-10 rounded">
                        <i class="fas fa-hand-holding-heart fa-2x text-primary"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-secondary mb-1">Active Borrows</h6>
                        <h4 class="fw-bold mb-0 text-body"><?= $active_borrows; ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 shadow-sm border-0 bg-body-tertiary">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 p-3 bg-success bg-opacity-10 rounded">
                        <i class="fas fa-coins fa-2x text-success"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-secondary mb-1">Total Fines</h6>
                        <h4 class="fw-bold mb-0 text-body">Rs. <?= number_format($total_fines, 2); ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 shadow-sm border-0 bg-body-tertiary">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 p-3 bg-info bg-opacity-10 rounded">
                        <i class="fas fa-check-circle fa-2x text-info"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-secondary mb-1">Books Available</h6>
                        <h4 class="fw-bold mb-0 text-body"><?= ($book_count - $active_borrows); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>