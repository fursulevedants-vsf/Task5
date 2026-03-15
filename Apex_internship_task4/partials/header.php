<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo isset($pageTitle) ? $pageTitle : 'Write Wave'; ?></title>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/style.css" />
  <script src="assets/script.js" defer></script>
</head>

<body>
  <nav class="glass-navbar">
    <div class="container">
      <a class="brand" href="welcome.php">
        Write<span class="brand-wave">🌊</span>Wave
      </a>
      <button class="hamburger" id="hamburger" aria-label="Toggle navigation">&#9776;</button>

      <ul class="nav-links" id="nav-links">
        <li><a class="nav-link" href="welcome.php">Home</a></li>
        <li><a class="nav-link" href="about.php">About</a></li>
        <?php if (Auth::isLoggedIn()): ?>
          <li><a class="nav-link" href="index.php">Dashboard</a></li>
          <?php if (Auth::can('create')): ?>
            <li><a class="nav-link" href="create.php">New Post</a></li>
          <?php endif; ?>
          <li><a class="nav-link" href="profile.php">Profile</a></li>
          <li><a class="nav-link" href="logout.php">Logout</a></li>
        <?php else: ?>
          <li><a class="nav-link" href="login.php">Login</a></li>
          <li><a class="nav-link" href="register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </nav>
  
  <div class="banner-text">
    Express your own thoughts, not AI's
  </div>

  <main class="main-content container">
