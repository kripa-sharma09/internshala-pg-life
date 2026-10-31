<?php
require_once __DIR__ . '/includes/config.php';
if (is_logged_in()) { header('Location: dashboard.php'); exit; }
$pageTitle = 'Create account';
$errors = [];
$name = $email = $phone = $college = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $phone = trim($_POST['phone'] ?? '');
    $college = trim($_POST['college'] ?? '');
    $gender = $_POST['gender'] ?? 'Prefer not to say';
    $password = $_POST['password'] ?? '';
    if ($name === '') $errors[] = 'Name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    if ($phone !== '' && !preg_match('/^[0-9+\-\s()]{7,20}$/', $phone)) $errors[] = 'Enter a valid phone number.';
    if (strlen($password) < 8) $errors[] = 'Password must contain at least 8 characters.';
    if (!in_array($gender, ['Female','Male','Other','Prefer not to say'], true)) $errors[] = 'Choose a valid gender option.';
    if (!$errors) {
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name,email,phone,gender,college,password_hash) VALUES (?,?,?,?,?,?)");
            $stmt->execute([$name,$email,$phone ?: null,$gender,$college ?: null,password_hash($password, PASSWORD_DEFAULT)]);
            set_flash('Account created successfully. You can now log in.');
            header('Location: login.php'); exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') $errors[] = 'An account with this email already exists.';
            else $errors[] = 'Could not create the account. Please try again.';
        }
    }
}
require __DIR__ . '/includes/header.php';
?>
<section class="auth-wrap container py-5"><div class="auth-card">
  <p class="eyebrow">WELCOME TO PG LIFE</p><h1 class="h2 mb-4">Create your account</h1>
  <?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
  <form method="post" novalidate>
    <div class="mb-3"><label class="form-label" for="name">Full name *</label><input class="form-control" id="name" name="name" value="<?= e($name) ?>" required maxlength="100"></div>
    <div class="mb-3"><label class="form-label" for="email">Email *</label><input class="form-control" type="email" id="email" name="email" value="<?= e($email) ?>" required maxlength="190"></div>
    <div class="mb-3"><label class="form-label" for="phone">Phone</label><input class="form-control" id="phone" name="phone" value="<?= e($phone) ?>" maxlength="20"></div>
    <div class="mb-3"><label class="form-label" for="college">College / workplace</label><input class="form-control" id="college" name="college" value="<?= e($college) ?>" maxlength="150"></div>
    <div class="mb-3"><label class="form-label" for="gender">Gender preference</label><select class="form-select" id="gender" name="gender"><option>Prefer not to say</option><option <?= (($_POST['gender'] ?? '')==='Female')?'selected':'' ?>>Female</option><option <?= (($_POST['gender'] ?? '')==='Male')?'selected':'' ?>>Male</option><option <?= (($_POST['gender'] ?? '')==='Other')?'selected':'' ?>>Other</option></select></div>
    <div class="mb-4"><label class="form-label" for="password">Password * (minimum 8 characters)</label><input class="form-control" type="password" id="password" name="password" required minlength="8"></div>
    <button class="btn btn-primary w-100" type="submit">Create account</button>
  </form>
  <p class="mt-3 mb-0 text-center">Already registered? <a href="login.php">Log in</a></p>
</div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
