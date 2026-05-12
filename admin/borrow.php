<?php
include '../includes/session_check.php';
include '../config/db.php';
include '../includes/header.php';
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