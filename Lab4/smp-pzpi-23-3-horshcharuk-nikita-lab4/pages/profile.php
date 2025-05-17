<?php
  require_once 'database/db.php';

  $errors = $_SESSION['profile_errors'] ?? [];
  unset($_SESSION['profile_errors']);

  if (!isset($_SESSION['user'])) {
    exit;
  }

  $username = $_SESSION['user'];

  $stmt = $pdo->prepare("SELECT name, surname, dob, description, photo FROM Users WHERE username = :username");
  $stmt->execute([':username' => $username]);
  $data = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$data) {
    $data = ['name'=>'', 'surname'=>'', 'dob'=>'', 'description'=>'', 'photo'=>''];
  }
?>

<div class="profile-page">
  <h1 class="profile-title">Профіль користувача</h1>

  <?php if ($errors): ?>
    <div class="profile-errors">
      <ul>
        <?php foreach ($errors as $error): ?>
          <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="POST" action="includes/profile_handler.php" enctype="multipart/form-data" class="profile-form">
    <div class="profile-column">
      <label class="profile-label">Фото:</label>
      <img id="photoPreview" src="<?= $data['photo'] ?>" alt="Profile Photo" class="profile-img">
      <input type="file" id="photoInput" name="photo" class="profile-file"  style="display: none;" onchange="previewPhoto(this)">
      <button type="button" class="profile-button" onclick="document.getElementById('photoInput').click();">
        Завантажити
      </button>
    </div>

    <div class="profile-column">
      <div class="profile-row-group">
        <div>
          <label class="profile-label">Ім'я:</label>
          <input type="text" name="name" value="<?= htmlspecialchars($data['name']) ?>" class="profile-input">
        </div>
        <div>
          <label class="profile-label">Прізвище:</label>
          <input type="text" name="surname" value="<?= htmlspecialchars($data['surname']) ?>" class="profile-input">
        </div>
        <div>
          <label class="profile-label">Дата народження:</label>
          <input type="date" name="dob" value="<?= htmlspecialchars($data['dob']) ?>" class="profile-date">
        </div>
      </div>

      <label class="profile-label">Короткий опис:</label>
      <textarea name="description" rows="8" class="profile-textarea"><?= htmlspecialchars($data['description']) ?></textarea>

      <br>
      <button type="submit" class="profile-button">Зберегти</button>
    </div>
  </form>
</div>

<script>
  let oldBlobURL = null;

  function previewPhoto(input) {
    const file = input.files[0];
    if (!file) return;

    const img = document.getElementById('photoPreview');

    if (oldBlobURL) {
      URL.revokeObjectURL(oldBlobURL);
    }

    oldBlobURL = URL.createObjectURL(file);
    img.src = oldBlobURL;
  }
</script>