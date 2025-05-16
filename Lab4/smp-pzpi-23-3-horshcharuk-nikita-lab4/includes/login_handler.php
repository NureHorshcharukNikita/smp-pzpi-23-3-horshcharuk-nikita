<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_POST['username']) || !isset($_POST['password']) || 
    empty(trim($_POST['username'])) || empty(trim($_POST['password']))) {
  $_SESSION['login_error'] = 'Будь ласка, заповніть усі поля';
  header('Location: /index.php?page=login');
  exit;
}

$creds = require 'credential.php';

if ($_POST['username'] === $creds['userName'] && $_POST['password'] === $creds['password']) {
  $_SESSION['user'] = $_POST['username'];
  $_SESSION['login_time'] = time();
  header('Location: /index.php?page=products');
  exit;
} else {
  $_SESSION['login_error'] = 'Невірні логін або пароль';
  header('Location: /index.php?page=login');
  exit;
}