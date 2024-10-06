<?php

/* -------------------------------------------------------------------------- */
/* Imports                                                                    */
/* -------------------------------------------------------------------------- */

require_once __DIR__ . "../../core/db.php";
require_once __DIR__ . "../../lib/utils.php";
// require_once __DIR__ . "/lib/constants.php";

/* -------------------------------------------------------------------------- */

if (!Util::isAdminLoggedIn()) {
  Util::redirect("./");
}

if (!isset($_GET["id"]) || strlen($_GET["id"]) <= 0) {
  echo "Produk tidak ditemukan";
  exit;
}

$productId = $_GET["id"];

$deleteQuery = "DELETE FROM products WHERE id = $productId";
$db->query($deleteQuery);

header("Location: ./product-list.php");
