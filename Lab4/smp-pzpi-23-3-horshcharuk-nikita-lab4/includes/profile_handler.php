<?php
$profileFile = '../data/profile_data.php';

$data = ['name'=>'', 'surname'=>'', 'dob'=>'', 'description'=>'', 'photo'=>''];
$errors = [];

if (file_exists($profileFile)) {
  $data = include $profileFile;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data['name'] = trim($_POST['name']);
  $data['surname'] = trim($_POST['surname']);
  $data['dob'] = $_POST['dob'];
  $data['description'] = trim($_POST['description']);

  if (strlen($data['name']) < 2) $errors[] = 'Ім\'я занадто коротке.';
  if (strlen($data['surname']) < 2) $errors[] = 'Прізвище занадто коротке.';
  if ((date('Y') - date('Y', strtotime($data['dob']))) < 16) $errors[] = 'Має бути не менше 16 років.';
  if (strlen($data['description']) < 50) $errors[] = 'Опис має бути не менше 50 символів.';

  if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
    $allowed = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($_FILES['photo']['type'], $allowed)) {
      $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
      $filename = '/data/uploads/profile_photo.' . $ext;
      move_uploaded_file($_FILES['photo']['tmp_name'], '..' . $filename);
      $data['photo'] = $filename;
    } else {
      $errors[] = 'Недійсний формат фото.';
    }
  }

  if (empty($errors)) {
    file_put_contents($profileFile, '<?php return ' . var_export($data, true) . ';');
    header("Location: /index.php?page=profile");
    exit;
  } else {
    session_start();
    $_SESSION['profile_errors'] = $errors;
    header("Location: /index.php?page=profile");
    exit;
  }
}
