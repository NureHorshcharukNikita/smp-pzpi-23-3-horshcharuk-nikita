<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../database/db.php';

if (!isset($_POST['username'], $_POST['password']) ||
    empty(trim($_POST['username'])) || empty(trim($_POST['password']))) {
  $_SESSION['login_error'] = 'Будь ласка, заповніть усі поля';
  header('Location: /index.php?page=login');
  exit;
}

$username = trim($_POST['username']);
$password = $_POST['password'];

$stmt = $pdo->prepare("SELECT * FROM Users WHERE username = :username");
$stmt->execute([':username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
  $_SESSION['user'] = $username;
  $_SESSION['login_time'] = time();
  header('Location: /index.php?page=products');
  exit;
} else {
  $_SESSION['login_error'] = 'Невірні логін або пароль';
  header('Location: /index.php?page=login');
  exit;
}
