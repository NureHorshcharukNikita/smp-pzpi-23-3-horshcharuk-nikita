<?php
  require_once 'includes/products.php';
  require_once 'includes/functions.php';
?>

<h1>Ваш кошик</h1>

<?php if (empty($_SESSION['cart'])): ?>
  <div id="empty-cart-container">
    <p>Кошик порожній.</p>
    <a href="index.php?page=products">Перейти до покупок</a>
  </div>
<?php else: ?>
  <section>
    <article>
      <table class="cart-table">
        <thead>
          <tr>
            <th>Id</th>
            <th>Назва</th>
            <th>Ціна</th>
            <th>Кількість</th>
            <th>Сума</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $total = 0;
          foreach ($_SESSION['cart'] as $item): 
            $product = $products[$item['id']] ?? null;
            if (!$product) continue;
            $sum = $product['price'] * $item['count'];
            $total += $sum;
          ?>
          <tr>
            <td><?= $product['id'] ?></td>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td><?= number_format($product['price'], 2) ?> грн</td>
            <td><?= $item['count'] ?></td>
            <td><?= number_format($sum, 2) ?> грн</td>
            <td>
              <a href="includes/actions.php?action=remove&id=<?= $item['id'] ?>" class="remove-btn">
                <i class="fas fa-trash"></i>
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
          <tr>
            <td><strong>Всього</strong></td>
            <td></td>
            <td></td>
            <td></td>
            <td><strong><?= number_format($total, 2) ?> грн</strong></td>
            <td></td>
          </tr>
        </tbody>
      </table>

      <div class="cart-actions">
        <form method="POST" action="includes/actions.php?action=clearAll">
          <button type="submit" class="clear-all-btn">Видалити все</button>
        </form>
        <button class="pay-btn">Оплатити</button>
      </div>
    </article>
  </section>
    
<?php endif; ?>