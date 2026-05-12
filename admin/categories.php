<?php
include '../includes/session_check.php';
include '../config/db.php';
date_default_timezone_set('Asia/Colombo');

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_category'])) {
    $cat_id   = trim($_POST['category_id']);
    $cat_name = trim($_POST['category_name']);

    if (!preg_match("/^C[0-9]{3,}$/", $cat_id)) {
        $message = "Invalid Category ID! Must start with 'C' followed by 3 digits (e.g. C001).";
        $message_type = "danger";
    } else {
        $date_modified = date("Y-m-d h:i:sa");
        $check = $conn->prepare("SELECT category_id FROM bookcategory WHERE category_id = ?");
        $check->bind_param("s", $cat_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "Category ID already exists.";
            $message_type = "danger";
        } else {
            $stmt = $conn->prepare("INSERT INTO bookcategory (category_id, category_Name, date_modified) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $cat_id, $cat_name, $date_modified);
            if ($stmt->execute()) {
                $message = "Category added successfully!";
                $message_type = "success";
            } else {
                $message = "Failed to add category.";
                $message_type = "danger";
            }
            $stmt->close();
        }
        $check->close();
    }
}

if (isset($_POST['update_category'])) {
    $edit_id      = trim($_POST['edit_category_id']);
    $cat_name     = trim($_POST['edit_category_name']);
    $date_modified = date("Y-m-d h:i:sa");
    $stmt = $conn->prepare("UPDATE bookcategory SET category_Name=?, date_modified=? WHERE category_id=?");
    $stmt->bind_param("sss", $cat_name, $date_modified, $edit_id);
    if ($stmt->execute()) {
        $message = "Category updated successfully!";
        $message_type = "success";
    } else {
        $message = "Update failed.";
        $message_type = "danger";
    }
    $stmt->close();
}

if (isset($_POST['delete_category'])) {
    $id = trim($_POST['delete_category_id']);
    if (!empty($id)) {
        $stmt = $conn->prepare("DELETE FROM bookcategory WHERE category_id = ?");
        $stmt->bind_param("s", $id);
        if ($stmt->execute()) {
            $message = "Category deleted successfully.";
            $message_type = "info";
        } else {
            $message = "Delete failed.";
            $message_type = "danger";
        }
        $stmt->close();
    }
}

$result = $conn->query("SELECT * FROM bookcategory");

include '../includes/header.php';
?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Book Categories</h2>
            <p class="text-secondary small">Manage book classification categories</p>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="fas fa-plus me-1"></i> Add New Category
        </button>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?> py-2 small">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Category ID</th>
                            <th>Category Name</th>
                            <th>Date Modified</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-primary"><?php echo htmlspecialchars($row['category_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['category_Name']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_modified']); ?></td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn btn-outline-secondary btn-sm border-0 p-1"
                                    onclick="openEditModal(
                                        '<?php echo htmlspecialchars($row['category_id'], ENT_QUOTES); ?>',
                                        '<?php echo htmlspecialchars($row['category_Name'], ENT_QUOTES); ?>'
                                    )">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm border-0 p-1"
                                    onclick="confirmDelete('<?php echo htmlspecialchars($row['category_id'], ENT_QUOTES); ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
     <br>
    <a href="dashboard.php" class="btn btn-secondary">
            Back to Dashboard
        </a>
</div>   
<form id="deleteForm" action="" method="POST" style="display:none;">
    <input type="hidden" name="delete_category" value="1">
    <input type="hidden" name="delete_category_id" id="delete_category_id_input">
</form>

<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small">Category ID (e.g. C001)</label>
                            <input type="text" name="category_id" class="form-control" placeholder="C001" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">Category Name</label>
                            <input type="text" name="category_name" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_category" class="btn btn-primary btn-sm">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small">Category ID</label>
                            <input type="text" id="edit_display_id" class="form-control" disabled>
                            <input type="hidden" name="edit_category_id" id="edit_category_id">
                        </div>
                        <div class="col-12">
                            <label class="form-label small">Category Name</label>
                            <input type="text" name="edit_category_name" id="edit_category_name" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_category" class="btn btn-primary btn-sm">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(category_id, category_name) {
    document.getElementById('edit_display_id').value    = category_id;
    document.getElementById('edit_category_id').value  = category_id;
    document.getElementById('edit_category_name').value = category_name;
    new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
}

function confirmDelete(category_id) {
    if (confirm('Are you sure you want to delete this category?')) {
        document.getElementById('delete_category_id_input').value = category_id;
        document.getElementById('deleteForm').submit();
    }
}
</script>

<?php include '../includes/footer.php'; ?>