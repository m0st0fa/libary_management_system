<?php
require 'config.php'; 

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT b.title, br.borrow_date, br.due_date, br.status
    FROM borrows br
    JOIN books b ON br.book_id = b.id
    WHERE br.user_id = ?
    ORDER BY br.borrow_date DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$borrows = [];
while ($row = $result->fetch_assoc()) {
    $borrows[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Borrowed Books</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>📚 My Borrowed Books</h2>
    <hr>
    <?php if(count($borrows) > 0): ?>
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Book Title</th>
                <th>Borrow Date</th>
                <th>Due Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach($borrows as $br): ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= htmlspecialchars($br['title']); ?></td>
                <td><?= htmlspecialchars($br['borrow_date']); ?></td>
                <td><?= htmlspecialchars($br['due_date']); ?></td>
                <td><?= htmlspecialchars($br['status']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>You have not borrowed any books yet.</p>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
