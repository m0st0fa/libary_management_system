<?php
require 'config.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();
if (!$book) {
    header('Location: search.php');
    exit;
}
require 'header.php';
?>
<h2><?= htmlspecialchars($book['title']) ?></h2>
<p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
<p><strong>ISBN:</strong> <?= htmlspecialchars($book['isbn']) ?></p>
<p><strong>Available copies:</strong> <?= (int)$book['copies'] ?></p>
<p><?= nl2br(htmlspecialchars($book['description'])) ?></p>

<?php if(!is_logged_in()): ?>
  <div class="alert alert-info">Please <a href="/login.php">login</a> to borrow this book.</div>
<?php else: ?>
  <?php if($book['copies'] < 1): ?>
    <div class="alert alert-warning">No copies available right now.</div>
  <?php else: ?>
    <form method="post" action="/borrow.php">
      <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
      <button class="btn btn-success">Borrow</button>
    </form>
  <?php endif; ?>
<?php endif; ?>

<?php require 'footer.php'; ?>
