<?php
include "config.php";

Auth::guard('create');

if (isset($_POST['create'])) {
  if (!Auth::verifyCsrf($_POST['csrf_token'] ?? '')) {
      $createError = 'Invalid CSRF token.';
  }
  else {
    $validator = new Validator($_POST);
    
    if ($validator->validate([
        'title'   => 'required|min:5|max:100',
        'content' => 'required|min:10'
    ])) {
      $title = sanitize($_POST['title']);
      $content = sanitize($_POST['content']);

      $stmt = $pdo->prepare("INSERT INTO posts (title, content, user_id) VALUES (:title, :content, :user_id)");
      $stmt->execute([
          'title'   => $title,
          'content' => $content,
          'user_id' => $_SESSION['user_id']
      ]);

      header("Location: index.php?msg=created");
      exit;
    } else {
      $createError = $validator->getFirstError();
    }
  }
}

$pageTitle = 'Create Post - Write Wave';
include 'partials/header.php';
?>

<div class="glass-card card-md">
  <div class="flex-between mb-3">
    <div>
      <h2>Create a new post</h2>
      <p>Write something new and share it with your readers.</p>
    </div>
    <a href="index.php" class="btn btn-outline btn-sm">Back to posts</a>
  </div>

  <?php if (isset($createError)): ?>
    <div class="alert alert-danger"><?php echo $createError; ?></div>
  <?php endif; ?>

  <form method="POST" novalidate>
    <input type="hidden" name="csrf_token" value="<?php echo Auth::csrfToken(); ?>">
    <div class="form-group">
      <label class="form-label">Title</label>
      <input type="text" name="title" class="glass-input" placeholder="Enter title" required />
    </div>

    <div class="form-group">
      <label class="form-label">Content</label>
      <textarea name="content" class="glass-input" placeholder="Write your post here..." required></textarea>
    </div>

    <button class="btn btn-success" name="create" type="submit">Create post</button>
  </form>
</div>

<?php include 'partials/footer.php'; ?>
