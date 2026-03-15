<?php
include "config.php";

Auth::guard();

// Fetch user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

// Count user's posts
$stmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE user_id = :user_id");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$totalPosts = $stmt->fetchColumn();

// Get recent posts
$stmt = $pdo->prepare("SELECT id, title, created_at FROM posts WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 5");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$recentPosts = $stmt->fetchAll();

$pageTitle = 'Profile - Write Wave';
include 'partials/header.php';
?>

<div class="glass-card card-md">
  <div class="profile-header mb-3">
    <div class="profile-avatar">
      <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
    </div>
    <div>
      <h2><?php echo htmlspecialchars($user['username']); ?></h2>
      <span class="role-badge role-<?php echo htmlspecialchars($user['role'] ?? 'editor'); ?>">
        <?php echo ucfirst(htmlspecialchars($user['role'] ?? 'editor')); ?>
      </span>
    </div>
  </div>

  <div class="profile-stats mb-3">
    <div class="profile-stat-card glass-card">
      <h3><?php echo $totalPosts; ?></h3>
      <p>Total Posts</p>
    </div>
    <div class="profile-stat-card glass-card">
      <h3><?php echo htmlspecialchars($user['created_at'] ? date('M Y', strtotime($user['created_at'])) : 'N/A'); ?></h3>
      <p>Member Since</p>
    </div>
  </div>

  <h3 class="mb-2">Recent Posts</h3>
  <?php if (!empty($recentPosts)): ?>
    <ul class="profile-post-list">
      <?php foreach ($recentPosts as $post): ?>
        <li class="profile-post-item">
          <a href="edit.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a>
          <span class="post-date"><?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p>You haven't written any posts yet. <a href="create.php">Write your first one!</a></p>
  <?php endif; ?>
</div>

<?php include 'partials/footer.php'; ?>
