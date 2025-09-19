<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Admin files are inside 'admin/' folder, so go up one level to load config
require '../config.php';

// Protect page: only admin
if (!is_logged_in() || !is_admin()) {
    header("Location: ../login.php");
    exit;
}

// Include header
require '../header.php';
?>
<h2>📊 Admin Dashboard</h2>
<ul>
  <li><a href="add_book.php">➕ Add Book</a></li>
  <li><a href="manage_books.php">📚 Manage Books</a></li>
  <li><a href="manage_users.php">👥 Manage Users</a></li>
</ul>

<?php require '../footer.php'; ?>
