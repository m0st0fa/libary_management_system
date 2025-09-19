<?php
require 'config.php';

$q = trim($_GET['q'] ?? '');
$books = [];

if ($q !== '') {
    $like = "%$q%";
    // Search by title or author (adjust columns if needed)
    $stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ? LIMIT 100");
    $stmt->bind_param('ss', $like, $like);
    $stmt->execute();
    $books = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

require 'header.php';
?>

<div class="container mt-4">
    <h2>🔍 Search Books</h2>
    <form class="mb-3" method="get">
        <div class="input-group">
            <input class="form-control" name="q" placeholder="Title or author" value="<?= htmlspecialchars($q) ?>">
            <button class="btn btn-outline-secondary">Search</button>
        </div>
    </form>

    <?php if($q === ''): ?>
        <p>Enter a query to find books.</p>
    <?php else: ?>
        <h5>Results (<?= count($books) ?>)</h5>
        <?php if(empty($books)): ?>
            <div class="alert alert-warning">No books found.</div>
        <?php else: ?>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Available</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach($books as $b): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($b['title']) ?></td>
                        <td><?= htmlspecialchars($b['author']) ?></td>
                        <td><?= (int)$b['quantity'] ?></td>
                        <td>
                            <a class="btn btn-sm btn-primary" href="3">View</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require 'footer.php'; ?>
