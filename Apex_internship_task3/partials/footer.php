  </main>

  <footer class="footer-bar">
    <div class="container">
      &copy; <?php echo date('Y'); ?> Write Wave. All rights reserved.
    </div>
  </footer>

  <?php if (!empty($_GET['msg'])): ?>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const msg = <?php echo json_encode($_GET['msg']); ?>;
        let title = '';
        switch (msg) {
          case 'created':
            title = 'Post created successfully';
            break;
          case 'updated':
            title = 'Post updated successfully';
            break;
          case 'deleted':
            title = 'Post deleted successfully';
            break;
          case 'registered':
            title = 'Registration successful';
            break;
          default:
            title = msg;
        }

        if (title && typeof showToast === 'function') {
          showToast(title, 'success');
        }
      });
    </script>
  <?php endif; ?>
</body>

</html>
