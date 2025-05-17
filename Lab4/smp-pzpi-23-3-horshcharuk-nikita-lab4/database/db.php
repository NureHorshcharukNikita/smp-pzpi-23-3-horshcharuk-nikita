<?php
$dbFile = __DIR__ . '/../data/shop.sqlite';

$dataDir = dirname($dbFile);
if (!is_dir($dataDir)) {
  mkdir($dataDir, 0777, true);
}

$uploadsDir = $dataDir . '/uploads';
if (!is_dir($uploadsDir)) {
  mkdir($uploadsDir, 0777, true);
}

$initDb = !file_exists($dbFile);

try {
  $pdo = new PDO("sqlite:$dbFile");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if ($initDb) {
    $pdo->exec("
      CREATE TABLE IF NOT EXISTS Products (
        id INTEGER PRIMARY KEY,
        name TEXT NOT NULL,
        price REAL NOT NULL CHECK(price >= 0)
      );
      
      CREATE TABLE IF NOT EXISTS Users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT,
        surname TEXT,
        dob TEXT,
        description TEXT,
        photo TEXT,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL
      );
    ");

    $products = [
      ['id' => 1, "name" => "Молоко пастеризоване", "price" => 12],
      ['id' => 2, "name" => "Хліб чорний", "price" => 9],
      ['id' => 3, "name" => "Сир білий", "price" => 21],
      ['id' => 4, "name" => "Сметана 20%", "price" => 25],
      ['id' => 5, "name" => "Кефір 1%", "price" => 19],
      ['id' => 6, "name" => "Вода газована", "price" => 18],
      ['id' => 7, "name" => "Печиво \"Весна\"", "price" => 14]
    ];

    $stmt = $pdo->prepare("INSERT OR IGNORE INTO Products (id, name, price) VALUES (:id, :name, :price)");
    foreach ($products as $p) {
      $stmt->execute([
        ':id' => $p['id'],
        ':name' => $p['name'],
        ':price' => $p['price']
      ]);
    }

    $pdo->prepare("
      INSERT INTO Users (name, surname, dob, description, photo, username, password)
      VALUES (:name, :surname, :dob, :description, :photo, :username, :password)
    ")->execute([
      ':name' => '',
      ':surname' => '',
      ':dob' => '',
      ':description' => '',
      ':photo' => '',
      ':username' => 'Test',
      ':password' => password_hash('123123', PASSWORD_DEFAULT)
    ]);
  }

} catch (PDOException $e) {
  die("Помилка підключення: " . $e->getMessage());
}
