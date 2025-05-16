<h1>Каталог товарів</h1>

<?php
  require_once 'includes/products.php';

  $error = $_SESSION['error'] ?? '';

  unset($_SESSION['error']);
?>

<?php if ($error): ?>
  <div class="error-message">
    <?= htmlspecialchars($error) ?>
  </div>
<?php endif; ?>

<section>
  <table class="product-table">
    <thead>
      <tr>
        <th>Товар</th>
        <th>Ціна</th>
        <th>Кількість</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product): ?>
      <tr>
        <form method="POST" action="includes/actions.php?action=add" class="buy-form">
          <td><?= htmlspecialchars($product['name']) ?></td>
          <td><?= $product['price'] ?> грн</td>
          <td>
            <input type="hidden" name="addIdInput" value="<?= $product['id'] ?>">
            <input type="number" name="addCountInput" value="1" class="quantity-input">
          </td>
          <td>
            <input type="submit" value="Купити" class="buy-button">
          </td>
        </form>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>