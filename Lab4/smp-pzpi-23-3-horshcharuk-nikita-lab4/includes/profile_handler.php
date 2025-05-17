<?php
session_start();
require_once __DIR__ . '/../database/db.php';

if (!isset($_SESSION['user'])) {
  exit;
}

$username = $_SESSION['user'];

$stmt = $pdo->prepare("SELECT * FROM Users WHERE username = :username");
$stmt->execute([':username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
  die('Користувач не знайдений.');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $surname = trim($_POST['surname']);
  $dob = $_POST['dob'];
  $description = trim($_POST['description']);
  $photo = $user['photo'];

  if (strlen($name) < 2) $errors[] = 'Ім\'я занадто коротке.';
  if (strlen($surname) < 2) $errors[] = 'Прізвище занадто коротке.';
  if ((date('Y') - date('Y', strtotime($dob))) < 16) $errors[] = 'Має бути не менше 16 років.';
  if (strlen($description) < 50) $errors[] = 'Опис має бути не менше 50 символів.';

  if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
    $allowed = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($_FILES['photo']['type'], $allowed)) {
      $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
      $filename = '/data/uploads/profile_photo_' . $user['id'] . '.' . $ext;
      move_uploaded_file($_FILES['photo']['tmp_name'], __DIR__ . '/..' . $filename);
      $photo = $filename;
    } else {
      $errors[] = 'Недійсний формат фото.';
    }
  }

  if (empty($errors)) {
    $stmt = $pdo->prepare("
      UPDATE Users SET
        name = :name,
        surname = :surname,
        dob = :dob,
        description = :description,
        photo = :photo
      WHERE username = :username
    ");
    $stmt->execute([
      ':name' => $name,
      ':surname' => $surname,
      ':dob' => $dob,
      ':description' => $description,
      ':photo' => $photo,
      ':username' => $username
    ]);

    header("Location: /index.php?page=profile");
    exit;
  } else {
    $_SESSION['profile_errors'] = $errors;
    header("Location: /index.php?page=profile");
    exit;
  }
}
