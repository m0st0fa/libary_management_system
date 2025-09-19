<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load config.php
if (file_exists(__DIR__ . '/config.php')) {
    require __DIR__ . '/config.php';
} elseif (file_exists(__DIR__ . '/../config.php')) {
    require __DIR__ . '/../config.php';
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rabbi Library</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Custom navbar tweaks */
    .navbar-brand { font-size: 1.5rem; }
    .nav-link.active { background-color: #0d6efd; color: #fff !important; border-radius: 5px; }
    .navbar-text { font-weight: 500; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="<?php echo (is_logged_in() && is_admin()) ? 'admin/index.php' : 'index.php'; ?>">
      Library
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <!-- Left Menu -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if (is_logged_in() && is_admin()): ?>
          <li class="nav-item">
            <a class="nav-link <?php echo $current_page=='index.php'?'active':''; ?>" href="admin/index.php">📊 Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $current_page=='add_book.php'?'active':''; ?>" href="admin/add_book.php">➕ Add Book</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $current_page=='reports.php'?'active':''; ?>" href="admin/reports.php">📊 Reports</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link <?php echo $current_page=='search.php'?'active':''; ?>" href="search.php">🔍 Search</a>
          </li>
          <?php if (is_logged_in()): ?>
            <li class="nav-item">
              <a class="nav-link <?php echo $current_page=='my_borrows.php'?'active':''; ?>" href="my_borrows.php">📖 My Borrows</a>
            </li>
          <?php endif; ?>
        <?php endif; ?>
      </ul>

      <!-- Right Menu -->
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <?php if (is_logged_in()): ?>
          <li class="nav-item">
            <a class="nav-link text-danger" href="<?php echo (is_admin()) ? '../logout.php' : 'logout.php'; ?>">🚪 Logout</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link <?php echo $current_page=='login.php'?'active':''; ?>" href="login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $current_page=='signup.php'?'active':''; ?>" href="signup.php">Signup</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Page Container -->
<div class="container mt-4">
