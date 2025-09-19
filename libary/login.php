<?php
require 'config.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $errors[] = "Provide email and password.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($row = $res->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                // ✅ Set session role correctly
                $_SESSION['user_id']   = $row['id'];
                $_SESSION['user_name'] = $row['username'];
                $_SESSION['role']      = $row['role']; // 'admin' or 'user'

                // Redirect based on role
                if ($row['role'] === 'admin') {
                    header('Location: admin_dashboard.php');
                } else {
                    header('Location: indexa.php');
                }
                exit;
            } else {
                $errors[] = "Invalid credentials.";
            }
        } else {
            $errors[] = "Invalid credentials.";
        }
    }
}

require 'header.php';
?>

<h2>Login</h2>

<?php if(isset($_GET['registered'])): ?>
  <div class="alert alert-success">Account created. Please log in.</div>
<?php endif; ?>

<?php if($errors): ?>
  <div class="alert alert-danger">
    <?= implode('<br>', array_map('htmlspecialchars', $errors)) ?>
  </div>
<?php endif; ?>

<form method="post">
  <div class="mb-3"><label>Email</label>
    <input class="form-control" name="email" type="email" required>
  </div>
  <div class="mb-3"><label>Password</label>
    <input class="form-control" name="password" type="password" required>
  </div>
  <button class="btn btn-primary">Login</button>
</form>

<?php require 'footer.php'; ?>
