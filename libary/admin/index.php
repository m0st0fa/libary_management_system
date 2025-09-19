<?php
require_once '../config.php';

if (!is_logged_in() || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Rabbi Library</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: "Segoe UI", sans-serif; }
    .sidebar { height: 100vh; background: #212529; padding-top: 20px; }
    .sidebar a { color: #ddd; text-decoration: none; display: block; padding: 12px 20px; border-radius: 5px; margin: 5px 10px; }
    .sidebar a:hover, .sidebar a.active { background: #0d6efd; color: #fff; }
    .content { padding: 20px; }
    .card { border-radius: 10px; }
    .table-actions button { margin-right: 5px; }
    .table-container { max-height: 400px; overflow-y: auto; }
  </style>
</head>
<body>
  <!-- Top Navbar -->
  <nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="../index.php">📚 Back to Home</a>
      <div>
        <a href="../logout.php" class="btn btn-sm btn-danger">Logout</a>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-2 sidebar">
        <a href="index.php" class="active">🏠 Dashboard</a>
        <a href="add_book.php">➕ Add Book</a>
        <a href="manage_books.php">✏️ Manage Books</a>
      </div>

      <!-- Content Area -->
      <div class="col-md-10 content">
        <h2>📊 Admin Dashboard</h2>
        <hr>

        <!-- Stats Cards -->
        <div class="row mb-4">
          <div class="col-md-4">
            <div class="card shadow-sm text-center p-3">
              <h5>Total Books</h5>
              <?php
              $result = $conn->query("SELECT COUNT(*) AS total FROM books");
              $books = $result->fetch_assoc();
              echo "<h3>" . $books['total'] . "</h3>";
              ?>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card shadow-sm text-center p-3">
              <h5>Total Users</h5>
              <?php
              $result = $conn->query("SELECT COUNT(*) AS total FROM users");
              $users = $result->fetch_assoc();
              echo "<h3>" . $users['total'] . "</h3>";
              ?>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card shadow-sm text-center p-3">
              <h5>Active Borrows</h5>
              <?php
              $result = $conn->query("SELECT COUNT(*) AS total FROM borrows WHERE status='borrowed'");
              $borrows = $result->fetch_assoc();
              echo "<h3>" . $borrows['total'] . "</h3>";
              ?>
            </div>
          </div>
        </div>

        <!-- Recent Borrowed Books -->
        <h4 class="mt-4 mb-3">📖 Recent Borrowed Books</h4>
        <div class="table-container mb-5">
          <table class="table table-striped table-bordered">
            <thead class="table-dark">
              <tr>
                <th>User</th>
                <th>Book</th>
                <th>Borrow Date</th>
                <th>Due Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $result = $conn->query("SELECT u.username, b.title, br.borrow_date, br.due_date, br.id AS borrow_id
                                      FROM borrows br
                                      JOIN users u ON br.user_id = u.id
                                      JOIN books b ON br.book_id = b.id
                                      ORDER BY br.borrow_date DESC LIMIT 5");
              while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['username']}</td>
                        <td>{$row['title']}</td>
                        <td>{$row['borrow_date']}</td>
                        <td>{$row['due_date']}</td>
                        <td>
                          <a href='edit_borrow.php?id={$row['borrow_id']}' class='btn btn-sm btn-warning'>Update</a>
                          <a href='delete_borrow.php?id={$row['borrow_id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure?');\">Delete</a>
                        </td>
                      </tr>";
              }
              ?>
            </tbody>
          </table>
        </div>

        <!-- Books List -->
        <h4 class="mt-5 mb-3">📚 Books List</h4>
        <div class="table-container">
          <table class="table table-striped table-bordered">
            <thead class="table-dark">
              <tr>
                <th>#</th>
                <th>Book Name</th>
                <th>Author</th>
                <th>Category</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $result = $conn->query("SELECT * FROM books ORDER BY id DESC");
              $i = 1;
              while ($book = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$i}</td>
                        <td>{$book['title']}</td>
                        <td>{$book['author']}</td>
                        <td>{$book['category']}</td>
                        <td>
                          <a href='edit_book.php?id={$book['id']}' class='btn btn-sm btn-warning'>Update</a>
                          <a href='delete_book.php?id={$book['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this book?');\">Delete</a>
                        </td>
                      </tr>";
                $i++;
              }
              ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
