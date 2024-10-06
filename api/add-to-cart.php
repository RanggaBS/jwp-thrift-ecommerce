<?php

/* -------------------------------------------------------------------------- */
/* Imports                                                                    */
/* -------------------------------------------------------------------------- */

require_once __DIR__ . "../../core/db.php";
require_once __DIR__ . "../../lib/utils.php";
// require_once __DIR__ . "../../lib/constants.php";

/* -------------------------------------------------------------------------- */

if (!Util::isLoggedIn()) {
	Util::redirect("../login.php");
}

// $userId = $_POST["productId"];
$userId = $_SESSION["userId"];
$productId = $_POST["productId"];

if (strlen($productId) <= 0) {
	Util::redirect("../");
}

/* -------------------------------------------------------------------------- */
function addToCart()
{
	global $db, $userId, $productId;

	$cartCheckQuery = "
		SELECT
			id
		FROM
			carts
		WHERE
			user_id = $userId
";
	$cartResult = $db->query($cartCheckQuery);

	$cartId = -1;
	if ($cartResult->num_rows > 0) {
		$cartId = $cartResult->fetch_assoc()["id"];
	} else {
		$createdAt = date("Y-m-d H:i:s");
		$createCartQuery = "
		INSERT INTO
			carts (user_id)
		VALUES
			('$userId')
	";
		$db->query($createCartQuery);
		$cartId = $db->insert_id;
	}

	/* -------------------------------------------------------------------------- */

	$checkProductQuery = "
	SELECT
		*
	FROM
		cart_products
	WHERE
		cart_id = $cartId
		AND product_id = $productId
";
	$productResult = $db->query($checkProductQuery);

	if ($productResult->num_rows > 0) {
		$updateCartProductQuery = "
	UPDATE cart_products
	SET
		quantity = quantity + 1,
		updated_at = NOW()
	WHERE
		cart_id = '$cartId'
		AND product_id = '$productId'
	";
		$db->query($updateCartProductQuery);
	} else {
		$inserCartProductQuery = "
		INSERT INTO
			cart_products (cart_id, product_id, quantity, updated_at)
		VALUES
			($cartId, $productId, 1, NOW())
	";
		$db->query($inserCartProductQuery);
	}
}

$db->close();

echo "
<script>
	alert('Produk berhasil ditambahkan ke keranjang!');
	window.location.href = '../product.php?id=$productId';
</script>";

// header("Location: ../product.php?id=$productId");
