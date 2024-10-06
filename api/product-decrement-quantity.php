<?php

/* -------------------------------------------------------------------------- */
/* Imports                                                                    */
/* -------------------------------------------------------------------------- */

require_once __DIR__ . "../../core/db.php";
require_once __DIR__ . "../../lib/utils.php";
// require_once __DIR__ . "/lib/constants.php";

/* -------------------------------------------------------------------------- */

if (!Util::isLoggedIn()) {
	Util::redirect("../");
}

session_start();
$userId = $_SESSION["userId"];
$cartProductId = $_POST["cartProductId"];

if (strlen($cartProductId) <= 0) {
	Util::redirect("../");
}

/* -------------------------------------------------------------------------- */

$checkProductQuery = "
	SELECT * FROM cart_products
	JOIN carts ON cart_products.cart_id = carts.id
	WHERE
		cart_products.id = $cartProductId
		AND carts.user_id = $userId
";
$productResult = $db->query($checkProductQuery);

if ($productResult->num_rows > 0) {
	$row = $productResult->fetch_assoc();
	if ($row["quantity"] > 1) {
		$decrementQuery = "
			UPDATE cart_products
			SET quantity = quantity - 1, updated_at = NOW()
			WHERE id = $cartProductId
		";
		$db->query($decrementQuery);
	} else {
		$deleteQuery = "
			DELETE FROM cart_products
			WHERE id = '$cartProductId'
		";
		$db->query($deleteQuery);
	}
}

$db->close();
Util::redirect("../cart.php");
