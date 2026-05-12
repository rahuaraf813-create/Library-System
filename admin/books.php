<?php
include '../includes/session_check.php';
include '../config/db.php';

$message = '';
$message_type = '';

if (isset($_POST['add_book'])) {
    $book_id     = trim($_POST['book_id']);
    $book_name   = trim($_POST['book_name']);
    $category_id = trim($_POST['category_id']);

    if (!preg_match('/^B\d{3,}$/', $book_id)) {
        $message = "Invalid Book ID! Must start with 'B' followed by 3 digits (e.g. B001).";
        $message_type = "danger";
    } else {
        $check = $conn->prepare("SELECT book_id FROM book WHERE book_id = ?");
        $check->bind_param("s", $book_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "Book ID already exists.";
            $message_type = "danger";
        } else {
            $stmt = $conn->prepare("INSERT INTO book (book_id, book_name, category_id) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $book_id, $book_name, $category_id);
            if ($stmt->execute()) {
                $message = "Book added successfully!";
                $message_type = "success";
            } else {
                $message = "Failed to add book.";
                $message_type = "danger";
            }
            $stmt->close();
        }
        $check->close();
    }
}

if (isset($_POST['update_book'])) {
    $edit_book_id = trim($_POST['edit_book_id']);
    $book_name    = trim($_POST['edit_book_name']);
    $category_id  = trim($_POST['edit_category_id']);

    $stmt = $conn->prepare("UPDATE book SET book_name=?, category_id=? WHERE book_id=?");
    $stmt->bind_param("sss", $book_name, $category_id, $edit_book_id);
    if ($stmt->execute()) {
        $message = "Book updated successfully!";
        $message_type = "success";
    } else {
        $message = "Update failed.";
        $message_type = "danger";
    }
    $stmt->close();
}

if (isset($_POST['delete_book'])) {
    $id = trim($_POST['delete_book_id']);
    if (!empty($id)) {
        $stmt = $conn->prepare("DELETE FROM book WHERE book_id = ?");
        $stmt->bind_param("s", $id);
        if ($stmt->execute()) {
            $message = "Book deleted successfully.";
            $message_type = "info";
        } else {
            $message = "Delete failed.";
            $message_type = "danger";
        }
        $stmt->close();
    }
}

$books = $conn->query("
    SELECT b.*, bc.category_Name
    FROM book b
    JOIN bookcategory bc ON b.category_id = bc.category_id
    ORDER BY b.book_id
");

$categories = $conn->query("SELECT * FROM bookcategory ORDER BY category_id");

include '../includes/header.php';
?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Books</h2>
            <p class="text-secondary small">Manage library book inventory</p>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBookModal">
            <i class="fas fa-plus me-1"></i> Add New Book
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
                            <th class="ps-4">Book ID</th>
                            <th>Book Name</th>
                            <th>Category</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $books->fetch_assoc()): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-primary"><?php echo htmlspecialchars($row['book_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['book_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['category_Name']); ?></td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn btn-outline-secondary btn-sm border-0 p-1"
                                    onclick="openEditModal(
                                        '<?php echo htmlspecialchars($row['book_id'], ENT_QUOTES); ?>',
                                        '<?php echo htmlspecialchars($row['book_name'], ENT_QUOTES); ?>',
                                        '<?php echo htmlspecialchars($row['category_id'], ENT_QUOTES); ?>'
                                    )">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm border-0 p-1"
                                    onclick="confirmDelete('<?php echo htmlspecialchars($row['book_id'], ENT_QUOTES); ?>')">
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
</div>

<form id="deleteForm" action="" method="POST" style="display:none;">
    <input type="hidden" name="delete_book" value="1">
    <input type="hidden" name="delete_book_id" id="delete_book_id_input">
</form>