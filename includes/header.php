<?php
$pageTitle = $pageTitle ?? 'PG Life';
$flashMessage = get_flash();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> | PG Life</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div id="page-loader" class="page-loader">
    <img src="img/progress_spinner.gif" alt="Loading...">
    <p>Loading, please wait...</p>
</div>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">
    <img src="img/logo.png" alt="PG Life" height="50">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <div class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
        <a class="nav-link" href="index.php">Home</a>
        <a class="nav-link" href="about.php">About Us</a>
        
        <?php if (is_logged_in()): ?>
          <span class="nav-link text-secondary">Hi, <?= e($_SESSION['user_name'] ?? 'User') ?></span>
          <a class="nav-link" href="dashboard.php">Dashboard</a>
          <a class="btn btn-outline-primary btn-sm" href="logout.php">Logout</a>
        <?php else: ?>
          <a class="nav-link" href="signup.php">Sign up</a>
          <a class="btn btn-primary btn-sm" href="login.php">Login</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
<main>
<?php if ($flashMessage): ?>
  <div class="container mt-3"><div class="alert alert-info alert-dismissible fade show" role="alert">
    <?= e($flashMessage) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div></div>
<?php endif; ?>
