<?php
require_once 'database/db.php';

$products = [];
$stmt = $pdo->query("SELECT id, name, price FROM Products");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $products[$row['id']] = $row;
}