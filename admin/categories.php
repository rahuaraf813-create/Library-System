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