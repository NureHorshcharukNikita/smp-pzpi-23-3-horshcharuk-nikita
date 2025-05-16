<header>
  <nav class="header-nav">
    <a href="index.php?page=home"><i class="fas fa-home"></i> Home</a> |
    <a href="index.php?page=products"><i class="fas fa-bars"></i> Products</a> |
    
    <?php if (!isset($_SESSION['user'])): ?>
      <a href="index.php?page=login"><i class="fas fa-sign-in-alt"></i> Login</a>
    <?php else: ?>
      <a href="index.php?page=cart"><i class="fas fa-shopping-cart"></i> Cart</a> |
      <a href="index.php?page=profile"><i class="fas fa-user"></i> Profile</a> |
      <a href="includes/logout_handler.php"><i class="fas fa-sign-out-alt"></i> Logout (<?= htmlspecialchars($_SESSION['user']) ?>)</a>
    <?php endif; ?>
  </nav>
</header>