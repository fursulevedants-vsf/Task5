<?php
include "config.php";

// Prevent browser caching so form fields don't persist after logout
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

if (isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit;
}

$registerError = '';

if (isset($_POST['register'])) {
  if (!Auth::verifyCsrf($_POST['csrf_token'] ?? '')) {
      $registerError = 'Invalid CSRF token.';
  } else {
    $validator = new Validator($_POST);
    
    if ($validator->validate([
        'username' => 'required|min:3|max:20|alphanumeric',
        'password' => 'required|min:6'
    ])) {
      // Sanitize after validation
      $username = sanitize($_POST['username']);
      $password = $_POST['password']; // Don't sanitize password as it's hashed

      // Check if username exists
      $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
      $check->execute(['username' => $username]);
      
      if ($check->fetchColumn() > 0) {
        $registerError = 'Username already taken.';
      } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->execute([
          'username' => $username,
          'password' => $hashed
        ]);
        
        $_SESSION['temp_username'] = $username;
        $_SESSION['temp_password'] = $password;
        
        header('Location: login.php?from=register');
        exit;
      }
    } else {
      $registerError = $validator->getFirstError();
    }
  }
}


$pageTitle = 'Register - Write Wave';
include 'partials/header.php';
?>

<div class="glass-card card-sm">
  <h2 class="text-center mb-3">Create an account</h2>

  <?php if ($registerError): ?>
    <div class="alert alert-danger">
      <?php echo htmlspecialchars($registerError); ?>
    </div>
  <?php endif; ?>

  <form method="POST" novalidate autocomplete="off">
    <input type="hidden" name="csrf_token" value="<?php echo Auth::csrfToken(); ?>">
    <div class="form-group">
      <label class="form-label">Username</label>
      <input type="text" name="username" class="glass-input" required autocomplete="username" autofocus />
    </div>

    <div class="form-group">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="glass-input" required autocomplete="new-password" />
    </div>

    <button class="btn btn-primary w-100" type="submit" name="register">Create account</button>
  </form>

  <div class="text-center mt-3">
    <p>Already have an account? <a href="login.php">Sign in</a></p>
  </div>
</div>

<?php include 'partials/footer.php'; ?>
