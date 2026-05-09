<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library Management System</title>
    <link rel="stylesheet" href="../assets/css/css/bootstrap.min.css">
    <script src="../assets/js/all.min.js"></script>
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
    <div class="container">
        <a class="navbar-brand font-weight-bold" href="dashboard.php">LIB-SYS ADMIN</a>
        
        <div class="navbar-nav">
            <a class="nav-link" href="dashboard.php">Dashboard</a>
            <a class="nav-link" href="books.php">Books</a>
            <a class="nav-link" href="categories.php">Categories</a>
            <a class="nav-link" href="members.php">Members</a>
            <a class="nav-link" href="borrow.php">Borrowing</a>
            <a class="nav-link" href="fines.php">Fines</a>
            <a class="nav-link text-warning" href="../auth/logout.php text-danger">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">