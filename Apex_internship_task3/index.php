<?php
include "config.php";

Auth::guard();

$user_id=$_SESSION['user_id'];

$limit=5;
$page=isset($_GET['page'])?$_GET['page']:1;
$start=($page-1)*$limit;

$search="";

if(isset($_GET['search'])){
$search=$_GET['search'];
}

$searchParam = "%$search%";

$query = "SELECT * FROM posts 
          WHERE user_id = :user_id 
          AND (title LIKE :search_title OR content LIKE :search_content) 
          LIMIT $start, $limit";

$stmt = $pdo->prepare($query);
$stmt->execute([
    'user_id'        => $user_id,
    'search_title'   => $searchParam,
    'search_content' => $searchParam
]);
$posts = $stmt->fetchAll();

$countQuery = "SELECT COUNT(*) as total FROM posts 
               WHERE user_id = :user_id 
               AND (title LIKE :search_title OR content LIKE :search_content)";

$stmt = $pdo->prepare($countQuery);
$stmt->execute([
    'user_id'        => $user_id,
    'search_title'   => $searchParam,
    'search_content' => $searchParam
]);
$totalPosts = $stmt->fetchColumn();

$totalPages = ceil($totalPosts / $limit);
?>

<?php
$pageTitle = 'Dashboard - Write Wave';
include 'partials/header.php';
?>

<div class="flex-between mb-3">
  <div>
    <h2>My Posts</h2>
    <p>Manage your posts and keep your blog up to date.</p>
  </div>
</div>

<form method="GET" class="search-form">
  <input type="text" name="search" placeholder="Search posts" value="<?php echo htmlspecialchars($search); ?>" class="glass-input" />
  <button class="btn btn-primary" type="submit">Search</button>
</form>

<div class="table-responsive">
  <table class="glass-table">
    <thead>
      <tr>
        <th>Title</th>
        <th>Content</th>
        <th class="text-right">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $row): ?>
          <tr>
            <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
            <td>
              <div class="text-truncate">
                <?php echo htmlspecialchars($row['content']); ?>
              </div>
            </td>
            <td>
              <div class="actions-cell text-right">
                <?php if (Auth::can('update')): ?>
                  <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-outline btn-sm">Edit</a>
                <?php endif; ?>
                <?php if (Auth::can('delete')): ?>
                  <button type="button" onclick="deletePost(<?php echo $row['id']; ?>)" class="btn btn-danger btn-sm">Delete</button>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="3">
            <div class="empty-state">
              <h3>No posts yet</h3>
              <p>Create your first post to get started.</p>
              <a href="create.php" class="btn btn-primary">Create a post</a>
            </div>
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php if ($totalPages > 1): ?>
  <ul class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <li class="page-item <?php echo $page === $i ? 'active' : ''; ?>">
        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
      </li>
    <?php endfor; ?>
  </ul>
<?php endif; ?>

<script>
  function deletePost(id) {
    if (typeof confirmAction === 'function') {
      confirmAction('This action cannot be undone. Are you sure you want to delete this post?', function() {
        window.location = 'delete.php?id=' + id;
      });
    } else {
      if (confirm('Are you sure you want to delete this post?')) {
        window.location = 'delete.php?id=' + id;
      }
    }
  }
</script>

<?php include 'partials/footer.php'; ?>
