<?php
require 'config.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['name'] ?? '');  // DB তে username field আছে
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    // Validation
    if (!$username || !$email || !$password || !$confirm) {
        $errors[] = "All fields are required.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if (empty($errors)) {
        // check existing email
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Email already registered.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            // এখানে DB অনুযায়ী username + password ব্যবহার করা হলো
            $ins  = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?,?,?)");
            $ins->bind_param('sss', $username, $email, $hash);

            if ($ins->execute()) {
                header('Location: login.php?registered=1');
                exit;
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
        }
    }
}
require 'header.php';
?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <h2 class="mb-4">Create an Account</h2>

      <?php if($errors): ?>
        <div class="alert alert-danger">
          <?php foreach($errors as $e): ?>
            <div><?= htmlspecialchars($e) ?></div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form method="post" class="card p-4 shadow-sm">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input class="form-control" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <input class="form-control" type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input class="form-control" type="password" name="password" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Confirm Password</label>
          <input class="form-control" type="password" name="confirm" required>
        </div>
        <button class="btn btn-primary w-100">Sign Up</button>
      </form>

      <div class="text-center mt-3">
        Already have an account? <a href="login.php">Log in here</a>
      </div>
    </div>
  </div>
</div>

<?php require 'footer.php'; ?>
