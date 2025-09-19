<?php
require_once '../config.php';

if (!is_admin()) {
    header('Location: index.php');
    exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = trim($_POST['title'] ?? '');
    $author   = trim($_POST['author'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $quantity = (int)($_POST['quantity'] ?? 1);

    if (!$title || !$author) {
        $errors[] = "Title and Author are required.";
    }

    if ($quantity < 1) $quantity = 1;

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO books (title, author, category, quantity) VALUES (?,?,?,?)");
        $stmt->bind_param('sssi', $title, $author, $category, $quantity);

        if ($stmt->execute()) {
            $success = "Book added successfully!";
        } else {
            $errors[] = "Failed to add book. Try again.";
        }
    }
}

require '../header.php';
?>

<div class="container py-5">
  <h2>Add New Book</h2>

  <?php if($errors): ?>
    <div class="alert alert-danger">
      <?php foreach($errors as $e): ?><div><?= htmlspecialchars($e) ?></div><?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <form method="post" class="card p-4 shadow-sm">
    <div class="mb-3">
      <label class="form-label">Title</label>
      <input class="form-control" name="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Author</label>
      <input class="form-control" name="author" value="<?= htmlspecialchars($_POST['author'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Category</label>
      <input class="form-control" name="category" value="<?= htmlspecialchars($_POST['category'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Quantity</label>
      <input class="form-control" name="quantity" type="number" value="<?= htmlspecialchars($_POST['quantity'] ?? 1) ?>">
    </div>
    <button class="btn btn-primary w-100">Add Book</button>
  </form>
</div>

<?php require '../footer.php'; ?>
