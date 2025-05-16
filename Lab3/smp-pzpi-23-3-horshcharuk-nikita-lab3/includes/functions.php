<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

function searchCartItem($id) {
	if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

	foreach ($_SESSION['cart'] as $index => $item) {
		if ($item['id'] == $id) {
			return $index;
		}
	}
	return -1;
}

function addCartItem($item) {
	$_SESSION['cart'][] = $item;
}

function incrementCartItemCount($itemNum, $count) {
	$_SESSION['cart'][$itemNum]['count'] += $count;
}
