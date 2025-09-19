<?php
require_once '../config.php';
if (!is_logged_in() || !is_admin()) { header('Location: /login.php'); exit; }
$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
  $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
}
header('Location: /admin/index.php');
exit;
