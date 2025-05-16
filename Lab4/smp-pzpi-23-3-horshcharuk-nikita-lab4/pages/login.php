<?php
  $error = $_SESSION['login_error'] ?? '';
  unset($_SESSION['login_error']);
?>

<div id="login-container">
  <form method="POST" action="includes/login_handler.php" class="login-form">
    <h1>Вхід</h1>

    <?php if ($error): ?>
      <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <label class="form-label">Ім’я користувача:
      <input type="text" name="username" class="form-input">
    </label>

    <label class="form-label">Пароль:
      <input type="password" name="password" class="form-input">
    </label>

    <button type="submit" class="login-button">Login</button>
  </form>
</div>