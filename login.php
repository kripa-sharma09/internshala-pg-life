<?php
require_once __DIR__ . '/includes/config.php';
if (is_logged_in()) { header('Location: dashboard.php'); exit; }
$pageTitle = 'Login';
$error = '';
$email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT id,name,email,password_hash FROM users WHERE email=? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_name'] = $user['name'];
        set_flash('Welcome back, '.$user['name'].'!');
        header('Location: dashboard.php'); exit;
    }
    $error = 'Email or password is incorrect.';
}
require __DIR__ . '/includes/header.php';
?>
<section class="auth-wrap container py-5"><div class="auth-card">
  <p class="eyebrow">GOOD TO SEE YOU AGAIN</p><h1 class="h2 mb-4">Log in</h1>
  <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
  <form method="post">
    <div class="mb-3"><label class="form-label" for="email">Email</label><input class="form-control" type="email" id="email" name="email" value="<?= e($email) ?>" required></div>
    <div class="mb-4"><label class="form-label" for="password">Password</label><input class="form-control" type="password" id="password" name="password" required></div>
    <button class="btn btn-primary w-100" type="submit">Log in</button>
  </form>
  <p class="mt-3 mb-0 text-center">New to PG Life? <a href="signup.php">Create an account</a></p>
</div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
