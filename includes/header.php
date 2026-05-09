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

<nav class="navbar bg-dark navbar-dark fixed-top shadow">
  <div class="container-fluid">
    <div class="d-flex align-items-center">
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand ms-3" href="dashboard.php">LIB-SYS ADMIN</a>
    </div>

    <div class="form-check form-switch me-2 d-flex align-items-center">
      <input class="form-check-input" type="checkbox" id="darkModeToggle" style="cursor: pointer;">
      <label class="form-check-label small ms-2" for="darkModeToggle" style="cursor: pointer; min-width: 110px;">
        <i id="themeIcon" class="fas fa-moon me-1"></i> 
        <span id="themeLabel">Dark Mode</span>
      </label>
    </div>

    <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="offcanvasNavbar" style="width: 280px;">
      <div class="offcanvas-header border-bottom border-secondary">
        <h5 class="offcanvas-title">Navigation</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
      </div>
      
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-start flex-grow-1">
          <li class="nav-item">
            <a class="nav-link active" href="dashboard.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="books.php"><i class="fas fa-book me-2"></i> Books Inventory</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="categories.php"><i class="fas fa-list me-2"></i> Categories</a>
          </li>

          <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
          <hr class="border-secondary">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">System Admin</a>
            <ul class="dropdown-menu dropdown-menu-dark">
              <li><a class="dropdown-item" href="users.php">Staff Management</a></li>
              <li><a class="dropdown-item" href="members.php">Library Members</a></li>
            </ul>
          </li>
          <?php endif; ?>

          <li class="nav-item mt-auto">
            <a class="nav-link text-danger" href="../auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<div class="container mt-4">