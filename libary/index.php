<?php
require_once 'config.php';

// Fetch books
$result = $conn->query("SELECT * FROM books ORDER BY created_at DESC");
$books = $result->fetch_all(MYSQLI_ASSOC);

require 'header.php';
?>

<!-- Borrow Success Alert -->
<?php if(isset($_GET['borrow_success']) && $_GET['borrow_success'] == 1): ?>
  <div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      🎉 Book borrowed successfully!
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
<?php endif; ?>

<!-- Hero Section -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="row align-items-center">
      <!-- Left Content -->
      <div class="col-md-6">
        <h1 class="display-4 fw-bold">
          Welcome to <span class="text-primary">Rabbi Library</span>
        </h1>
        <p class="lead">Discover thousands of books, borrow instantly, and enjoy seamless account management.</p>
        <div class="mt-4">
          <a href="/search.php" class="btn btn-primary btn-lg me-2">
            <i class="bi bi-search"></i> Search Books
          </a>
          <?php if(!is_logged_in()): ?>
            <a href="/signup.php" class="btn btn-outline-dark btn-lg">
              <i class="bi bi-person-plus"></i> Create Account
            </a>
          <?php else: ?>
            <a href="/my_borrows.php" class="btn btn-success btn-lg">
              <i class="bi bi-journal-bookmark"></i> My Borrows
            </a>
          <?php endif; ?>
        </div>
      </div>
      <!-- Right Image -->
      <div class="col-md-6 text-center mt-4 mt-md-0">
        <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=900" 
             class="img-fluid rounded-4 shadow-lg" alt="Library">
      </div>
    </div>
  </div>
</section>

<!-- Book Collection Section -->
<section class="py-5 bg-white">
  <div class="container">
    <h2 class="text-center mb-5 fw-bold">📚 Available Books</h2>
    <div class="row g-4">
      <?php if (count($books) > 0): ?>
        <?php foreach ($books as $book): ?>
          <div class="col-md-4 col-sm-6">
            <div class="card h-100 shadow-sm hover-effect">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
                <p class="text-muted mb-1"><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
                <p class="text-muted mb-1"><strong>Category:</strong> <?= htmlspecialchars($book['category']) ?></p>
                <p class="text-muted mb-3"><strong>Available:</strong> <?= (int)$book['quantity'] ?></p>
                <div class="mt-auto">
                  <?php if (is_logged_in()): ?>
                    <a href="borrow.php?book_id=<?= $book['id'] ?>" class="btn btn-primary w-100">
                      <i class="bi bi-book"></i> Borrow
                    </a>
                  <?php else: ?>
                    <a href="login.php" class="btn btn-outline-dark w-100">
                      <i class="bi bi-box-arrow-in-right"></i> Login to Borrow
                    </a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-center">No books available right now.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<style>
.hover-effect {
  transition: transform 0.3s, box-shadow 0.3s;
}
.hover-effect:hover {
  transform: translateY(-8px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}
</style>

<?php require 'footer.php'; ?>
