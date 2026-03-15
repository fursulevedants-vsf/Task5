<?php
include "config.php";

Auth::guard('update');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $id, 'user_id' => $_SESSION['user_id']]);
$row = $stmt->fetch();

if (!$row) {
  header('Location: index.php');
  exit;
}

if (isset($_POST['update'])) {
  if (!Auth::verifyCsrf($_POST['csrf_token'] ?? '')) {
      $editError = 'Invalid CSRF token.';
  } else {
    $validator = new Validator($_POST);
    
    if ($validator->validate([
        'title'   => 'required|min:5|max:100',
        'content' => 'required|min:10'
    ])) {
      $title = sanitize($_POST['title']);
      $content = sanitize($_POST['content']);

      $stmt = $pdo->prepare("UPDATE posts SET title = :title, content = :content WHERE id = :id AND user_id = :user_id");
      $stmt->execute([
          'title'   => $title,
          'content' => $content,
          'id'      => $id,
          'user_id' => $_SESSION['user_id']
      ]);
      
      header('Location: index.php?msg=updated');
      exit;
    } else {
      $editError = $validator->getFirstError();
    }
  }
}

$pageTitle = 'Edit Post - Write Wave';
include 'partials/header.php';
?>

<div class="glass-card card-md">
  <div class="flex-between mb-3">
    <div>
      <h2>Edit post</h2>
      <p>Update the content and save changes.</p>
    </div>
    <a href="index.php" class="btn btn-outline btn-sm">Back to posts</a>
  </div>

  <?php if (isset($editError)): ?>
    <div class="alert alert-danger"><?php echo $editError; ?></div>
  <?php endif; ?>

  <form method="POST" novalidate>
    <input type="hidden" name="csrf_token" value="<?php echo Auth::csrfToken(); ?>">
    <div class="form-group">
      <label class="form-label">Title</label>
      <input type="text" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" class="glass-input" required />
    </div>

    <div class="form-group">
      <label class="form-label">Content</label>
      <textarea name="content" class="glass-input" required><?php echo htmlspecialchars($row['content']); ?></textarea>
    </div>

    <button class="btn btn-primary" name="update" type="submit">Update post</button>
  </form>
</div>

<?php include 'partials/footer.php'; ?>
