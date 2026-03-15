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

$loginError = false;

$registerSuccess = false;
$temp_username = '';
$temp_password = '';

if (isset($_GET['from']) && $_GET['from'] === 'register') {
  if (isset($_SESSION['temp_username'], $_SESSION['temp_password'])) {
    $temp_username = $_SESSION['temp_username'];
    $temp_password = $_SESSION['temp_password'];
    unset($_SESSION['temp_username'], $_SESSION['temp_password']);
    $registerSuccess = true;
  }
}

if (isset($_POST['login'])) {
  if (!Auth::verifyCsrf($_POST['csrf_token'] ?? '')) {
      $loginError = 'Invalid CSRF token.';
  } else {
    $validator = new Validator($_POST);
    
    if ($validator->validate([
        'username' => 'required',
        'password' => 'required'
    ])) {
      $username = sanitize($_POST['username']);
      $password = $_POST['password'];

      $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
      $stmt->execute(['username' => $username]);
      $user = $stmt->fetch();

      if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'] ?? 'editor';
        
        // Prevent session fixation
        session_regenerate_id(true);
        
        header('Location: index.php');
        exit;
      }

      $loginError = 'Invalid username or password.';
    } else {
      $loginError = $validator->getFirstError();
    }
  }
}

$pageTitle = 'Login - Write Wave';
include 'partials/header.php';
?>

<div class="glass-card card-sm">
  <h2 class="text-center mb-3">Sign in to your account</h2>

  <?php if ($registerSuccess): ?>
    <div class="alert alert-success">
      Account created successfully! Please sign in with your credentials.
    </div>
  <?php endif; ?>
  <?php if ($loginError): ?>
    <div class="alert alert-danger">
      <?php echo htmlspecialchars(is_string($loginError) ? $loginError : 'Invalid username or password.'); ?>
    </div>
  <?php endif; ?>

  <form method="POST" novalidate autocomplete="off">
    <input type="hidden" name="csrf_token" value="<?php echo Auth::csrfToken(); ?>">
    <div class="form-group">
      <label class="form-label">Username</label>
      <input type="text" name="username" class="glass-input" required autocomplete="off" value="<?php echo htmlspecialchars($temp_username); ?>" autofocus />
    </div>

    <div class="form-group">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="glass-input" required autocomplete="off" value="<?php echo htmlspecialchars($temp_password); ?>" />
    </div>

    <button class="btn btn-primary w-100" name="login" type="submit">
      Sign in
    </button>
  </form>

  <div class="text-center mt-3">
    <p>Don't have an account? <a href="register.php">Create one</a></p>
  </div>
</div>

<?php include 'partials/footer.php'; ?>
