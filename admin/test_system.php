<?php
include '../config/db.php';
include '../includes/header.php';
include '../includes/session_check.php';
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-10">
        <div class="card shadow border-0">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">System Integration Test</h5>
            </div>
            <div class="card-body p-4">
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="p-3 border rounded bg-light">
                            <h6>Database Integrity</h6>
                            <hr>
                            <?php if($conn): ?>
                                <div class="text-success font-weight-bold">
                                    Connected Successfully
                                </div>
                                <small class="text-muted">Host: localhost | Database: library_system</small>
                            <?php else: ?>
                                <div class="text-danger font-weight-bold">
                                    Connection Failed
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="p-3 border rounded bg-light">
                            <h6>Environment Specs</h6>
                            <hr>
                            <ul class="list-unstyled mb-0 small">
                                <li><strong>PHP Version:</strong> <?php echo phpversion(); ?></li>
                                <li><strong>Server:</strong> <?php echo $_SERVER['SERVER_SOFTWARE']; ?></li>
                                <li><strong>Protocol:</strong> <?php echo $_SERVER['SERVER_PROTOCOL']; ?></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-2">
                    <h5>System Check:</h5>
                    <p class="mb-0">Verify that the navigation bar renders correctly and the database connection status is active before proceeding with module development.</p>
                </div>

                <div class="text-center mt-4">
                    <a href="../auth/login.php" class="btn btn-outline-secondary">Go to Login</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
include '../includes/footer.php'; 
?>