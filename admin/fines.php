<?php
include '../includes/session_check.php';
include '../config/db.php';

$message = '';
$message_type = '';


if (isset($_POST['add_fine'])) {
    $fine_id = trim($_POST['fine_id']);
    $book_id = trim($_POST['book_id']);
    $member_id = trim($_POST['member_id']);
    $fine_amount = $_POST['fine_amount'];

    try {
        $stmt = $conn->prepare("INSERT INTO fine (fine_id, book_id, member_id, fine_amount, fine_date_modified) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssd", $fine_id, $book_id, $member_id, $fine_amount);
        if ($stmt->execute()) {
            $message = "Fine issued successfully.";
            $message_type = "success";
        }
    } catch (mysqli_sql_exception $e) {
        $message = "Error: Invalid IDs provided.";
        $message_type = "danger";
    }
}


if (isset($_POST['delete_fine'])) {
    $id = $_POST['delete_fine_id'];
    $stmt = $conn->prepare("DELETE FROM fine WHERE fine_id = ?");
    $stmt->bind_param("s", $id);
    if ($stmt->execute()) {
        $message = "Fine record deleted.";
        $message_type = "info";
    }
}


$query = "SELECT fine.*, book.book_name, member.first_name 
          FROM fine 
          JOIN book ON fine.book_id = book.book_id 
          JOIN member ON fine.member_id = member.member_id";
$result = $conn->query($query);

include '../includes/header.php';
?>