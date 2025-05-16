<?php session_start(); ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Продовольчий магазин весна</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  </head>
  <body>
    <div id="main-container">
      <header>
        <nav class="header-nav">
          <a href="index.php?page=home"><i class="fas fa-home"></i> Home</a> |
          <a href="index.php?page=products"><i class="fas fa-bars"></i> Products</a> |
          <a href="index.php?page=cart"><i class="fas fa-shopping-cart"></i> Cart</a>
        </nav>
      </header>
      <main>
        <?php
          $page = $_GET['page'] ?? 'home';
          $contentFile = 'pages/' . $page . '.php';

          if (file_exists($contentFile)) {
            include $contentFile;
          } else {
            echo '<h1>404 - Сторінку не знайдено</h1>';
          }
        ?>
      </main>
      <footer>
        <nav class="footer-nav">
          <a href="index.php?page=home">Home</a> |
          <a href="index.php?page=products">Products</a> |
          <a href="index.php?page=cart">Cart</a> |
          <a href="index.php?page=about">About Us</a>
        </nav>
      </footer>
    </div>
  </body>
</html>
