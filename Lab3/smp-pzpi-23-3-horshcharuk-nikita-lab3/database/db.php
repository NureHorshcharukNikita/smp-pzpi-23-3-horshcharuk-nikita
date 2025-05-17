<?php
$dbFile = 'data/shop.sqlite';

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

    $stmt = $pdo->prepare("INSERT INTO Products (id, name, price) VALUES (:id, :name, :price)");

    foreach ($products as $product) {
      $stmt->execute([
        ':id' => $product['id'],
        ':name' => $product['name'],
        ':price' => $product['price']
      ]);
    }
  }
} catch (PDOException $e) {
  die("Помилка підключення: " . $e->getMessage());
}
