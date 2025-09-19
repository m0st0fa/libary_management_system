<?php
require_once '../config.php';
if (!is_logged_in() || !is_admin()) {
    header('Location: /login.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: /admin/index.php');
    exit;
}

/* select only the real columns we have */
$stmt = $conn->prepare("SELECT id, title, author, category, quantity, created_at FROM books WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();

if (!$book) {
    header('Location: /admin/index.php');
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = trim($_POST['title'] ?? '');
    $author   = trim($_POST['author'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $quantity = intval($_POST['quantity'] ?? 1);

    if ($title === '' || $author === '') {
        $errors[] = "Title and author required.";
    }

    if (empty($errors)) {
        $upd = $conn->prepare("UPDATE books SET title = ?, author = ?, category = ?, quantity = ? WHERE id = ?");
        $upd->bind_param('sssii', $title, $author, $category, $quantity, $id);
        if ($upd->execute()) {
            header('Location: /admin/index.php');
            exit;
        } else {
            $errors[] = "DB error: " . $conn->error;
        }
    }
}

require '../header.php';
?>

<div class="container mt-3">
  <h2>Edit Book</h2>

  <?php if ($errors): ?>
    <div class="alert alert-danger">
      <?= htmlspecialchars(implode('<br>', $errors)) ?>
    </div>
  <?php endif; ?>

  <form method="post" class="mt-3">
    <div class="mb-3">
      <label class="form-label">Title</label>
      <input class="form-control" name="title" required value="<?= htmlspecialchars($book['title'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Author</label>
      <input class="form-control" name="author" required value="<?= htmlspecialchars($book['author'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Category</label>
      <input class="form-control" name="category" value="<?= htmlspecialchars($book['category'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Quantity</label>
      <input class="form-control" name="quantity" type="number" min="0" value="<?= (int)($book['quantity'] ?? 1) ?>">
    </div>

    <button class="btn btn-primary">Update Book</button>
    <a class="btn btn-secondary ms-2" href="/admin/index.php">Cancel</a>
  </form>
</div>

<?php require '../footer.php'; ?>
