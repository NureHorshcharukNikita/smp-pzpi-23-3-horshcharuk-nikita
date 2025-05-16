<?php
require_once 'functions.php';

if (isset($_GET['action'])) {
	switch ($_GET['action']) {
		case 'add':
			doAdd();
			break;
		case 'remove':
			doRemove();
			break;
		case 'clearAll':
			doClearAll();
			break;
	}
}

function doAdd() {
	if (isset($_POST['addIdInput'])) {
		$id = (int)$_POST['addIdInput'];

		$addCount = isset($_POST['addCountInput']) && (int)$_POST['addCountInput'] > 0
			? (int)$_POST['addCountInput']
			: 1;

		$itemNum = searchCartItem($id);
		$currentCount = 0;

		if ($itemNum >= 0) {
			$currentCount = $_SESSION['cart'][$itemNum]['count'];
		}

		validateQuantity($id, $addCount, $currentCount);

		if ($itemNum < 0) {
				$item = ['id' => $id, 'count' => $addCount];
				addCartItem($item);
		} else {
				incrementCartItemCount($itemNum, $addCount);
		}
	}

	header('Location: /index.php?page=cart');
	exit;
}

function doRemove() {
	if (isset($_GET['id'])) {
		$id = (int)$_GET['id'];
		$itemNum = searchCartItem($id);

		if ($itemNum >= 0) {
			unset($_SESSION['cart'][$itemNum]);
			$_SESSION['cart'] = array_values($_SESSION['cart']);
		}
	}

	header('Location: /index.php?page=cart');
	exit;
}

function doClearAll() {
	unset($_SESSION['cart']);

  header('Location: /index.php?page=cart');
	exit;
}

function validateQuantity($id, $addCount, $currentCount) {
    $newTotalCount = $currentCount + $addCount;

    $errors = [];

    if ($addCount < 1) {
			$errors[] = "Мінімальна кількість — 1.";
    }

    if ($newTotalCount > 99) {
      $errors[] = "Не можна додати більше 99 одиниць товару (зараз: $currentCount).";
    }

    if (!empty($errors)) {
			$_SESSION['error'] = implode(' ', $errors);
			header('Location: /index.php?page=products');
			exit;
    }
}
