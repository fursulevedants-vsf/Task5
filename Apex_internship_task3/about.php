<?php
include "config.php";

$pageTitle = 'About - Write Wave';
include 'partials/header.php';
?>

<div class="glass-card card-md">
  <h1 class="mb-2">About Write Wave</h1>
  <p class="mb-3">Write Wave is a secure, modern blogging platform built for writers who value simplicity and privacy.</p>
  
  <h3 class="mb-1">🎯 Our Mission</h3>
  <p class="mb-3">To give everyone a distraction-free space to write, share, and manage their thoughts — powered by real human creativity, not AI.</p>

  <h3 class="mb-1">🔐 Security First</h3>
  <p class="mb-3">Every line of code is written with security in mind. From PDO prepared statements to CSRF tokens, your data is protected against common web vulnerabilities.</p>

  <h3 class="mb-1">🛠️ Technology</h3>
  <ul class="about-list mb-3">
    <li><strong>Backend:</strong> PHP with PDO (MySQL)</li>
    <li><strong>Frontend:</strong> Pure CSS with glassmorphism design</li>
    <li><strong>Security:</strong> RBAC, CSRF protection, input validation &amp; sanitization</li>
    <li><strong>Design:</strong> Responsive, mobile-first with smooth animations</li>
  </ul>

  <h3 class="mb-1">👤 Roles</h3>
  <div class="roles-grid mb-3">
    <div class="role-badge role-admin">Admin — Full access + user management</div>
    <div class="role-badge role-editor">Editor — Create, read, update, delete posts</div>
    <div class="role-badge role-viewer">Viewer — Read-only access</div>
  </div>

  <div class="text-center mt-3">
    <a href="register.php" class="btn btn-primary">Join Write Wave</a>
  </div>
</div>

<?php include 'partials/footer.php'; ?>
