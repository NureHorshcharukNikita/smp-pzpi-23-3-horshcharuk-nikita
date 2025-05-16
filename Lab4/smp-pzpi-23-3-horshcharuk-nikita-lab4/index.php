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
      <?php require_once 'includes/header.php'; ?>
      <main>
        <?php
          $page = $_GET['page'] ?? 'home';
          $publicPages = ['login'];
          $contentFile = 'pages/' . $page . '.php';

          if (!file_exists($contentFile)) {
            echo '<h1>Сторінку не знайдено!</h1>';
          }
          else {
            if (!isset($_SESSION['user']) && !in_array($page, $publicPages)) {
              $contentFile = 'pages/page404.php';
            }
            
            include $contentFile;
          }
        ?>
      </main>
      <?php require_once 'includes/footer.php'; ?>
    </div>
  </body>
</html>
