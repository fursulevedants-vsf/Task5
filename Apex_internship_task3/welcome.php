<?php
include "config.php";

// If already logged in, go to dashboard
if (Auth::isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Write Wave - Express Yourself';
include 'partials/header.php';
?>

<div class="hero-section">
  <div class="hero-content">
    <h1 class="hero-title">Write your story.<br><span class="hero-highlight">Share it with the world.</span></h1>
    <p class="hero-subtitle">Write Wave is a modern blogging platform where you can express your own thoughts, ideas, and creativity — no AI, just you.</p>
    <div class="hero-actions">
      <a href="register.php" class="btn btn-primary btn-lg">Get Started Free</a>
      <a href="about.php" class="btn btn-outline btn-lg">Learn More</a>
    </div>
  </div>

  <div class="hero-stats">
    <div class="stat-card glass-card">
      <span class="stat-icon">✍️</span>
      <h3 class="stat-number"><?php
        $count = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
        echo $count;
      ?></h3>
      <p>Posts Written</p>
    </div>
    <div class="stat-card glass-card">
      <span class="stat-icon">👥</span>
      <h3 class="stat-number"><?php
        $count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        echo $count;
      ?></h3>
      <p>Writers Joined</p>
    </div>
    <div class="stat-card glass-card">
      <span class="stat-icon">🔒</span>
      <h3 class="stat-number">100%</h3>
      <p>Secure & Private</p>
    </div>
  </div>
</div>

<div class="features-section">
  <h2 class="text-center mb-3">Why Write Wave?</h2>
  <div class="features-grid">
    <div class="feature-card glass-card">
      <div class="feature-icon">🛡️</div>
      <h3>Secure by Design</h3>
      <p>Built with industry-standard security: prepared statements, CSRF protection, role-based access, and more.</p>
    </div>
    <div class="feature-card glass-card">
      <div class="feature-icon">⚡</div>
      <h3>Fast & Simple</h3>
      <p>No clutter, no distractions. Just a clean editor to write, edit, and manage your posts.</p>
    </div>
    <div class="feature-card glass-card">
      <div class="feature-icon">🎨</div>
      <h3>Beautiful Interface</h3>
      <p>A modern glassmorphism design that looks stunning and works perfectly on any device.</p>
    </div>
  </div>
</div>

<?php include 'partials/footer.php'; ?>
