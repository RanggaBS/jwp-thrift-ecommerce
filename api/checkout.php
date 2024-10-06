<?php

/* -------------------------------------------------------------------------- */
/* Imports                                                                    */
/* -------------------------------------------------------------------------- */

require_once __DIR__ . "../../core/db.php";
require_once __DIR__ . "../../lib/utils.php";
// require_once __DIR__ . "../../lib/constants.php";

/* -------------------------------------------------------------------------- */

if (!Util::isLoggedIn()) {
	Util::redirect("../");
}

$userId = $_SESSION["userId"];
$cartQuery = "SELECT id FROM carts WHERE user_id = $userId";
$cartResult = $db->query($cartQuery);

if ($cartResult->num_rows > 0) {
	$cartRow = $cartResult->fetch_assoc();
	$cartId = $cartRow["id"];

	$sql = "
		SELECT
			cart_products.product_id,
			cart_products.quantity,
			products.price
		FROM cart_products
		JOIN
			products ON cart_products.product_id = products.id
		WHERE
			cart_products.cart_id = $cartId
	";
	$result = $db->query($sql);

	if ($result->num_rows > 0) {
		$totalPrice = 0;
		$orderItems = [];

		while ($row = $result->fetch_assoc()) {
			$productId = $row["product_id"];
			$quantity = $row["quantity"];
			$price = $row["price"];

			$subTotal = $price * $quantity;
			$totalPrice += $subTotal;

			$orderItems[] = [
				"product_id" => $productId,
				"quantity" => $quantity,
				"price" => $price,
			];
		}

		$orderQuery = "
			INSERT INTO orders (user_id, total_price)
			VALUES ($userId, $totalPrice)
		";
		if ($db->query($orderQuery)) {
			$orderId = $db->insert_id;

			foreach ($orderItems as $item) {
				$productId = $item["product_id"];
				$quantity = $item["quantity"];
				$price = $item["price"];

				$orderItemQuery = "
					INSERT INTO order_products (order_id, product_id, quantity, price)
					VALUES ($orderId, $productId, $quantity, $price)
				";
				$db->query($orderItemQuery);
			}

			$deleteQuery = "DELETE FROM cart_products WHERE cart_id = $cartId";
			$db->query($deleteQuery);

			echo "Checkout berhasil!";

			// Util::redirect("../cart.php");
		} else {
			echo "Terjadi kesalahan: $db->error";
		}
	} else {
		echo "Keranjangmu kosong.";
	}
} else {
	echo "Keranjangmu kosong.";
}