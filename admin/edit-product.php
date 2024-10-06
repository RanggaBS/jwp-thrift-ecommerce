<?php

/* -------------------------------------------------------------------------- */
/* Imports                                                                    */
/* -------------------------------------------------------------------------- */

require_once __DIR__ . "../../core/db.php";
require_once __DIR__ . "../../lib/utils.php";
// require_once __DIR__ . "/lib/constants.php";

/* -------------------------------------------------------------------------- */
/* Check session                                                              */
/* -------------------------------------------------------------------------- */

if (!Util::isAdminLoggedIn()) {
  Util::redirect("./");
}

/* -------------------------------------------------------------------------- */

if (!isset($_GET["id"]) || strlen($_GET["id"]) <= 0) {
  Util::redirect("./dashboard/");
}

$productId = $_GET["id"];
$query = "
  SELECT
    products.*,
    product_images.image_url
  FROM
    products
    LEFT JOIN product_images ON products.id = product_images.product_id
  WHERE products.id = $productId
";
$result = $db->query($query);

$product = null;
if ($result->num_rows > 0) {
  $product = $result->fetch_assoc();
} else {
  echo "Produk tidak ditemukan.";
  exit;
}

/* -------------------------------------------------------------------------- */
/* Get Categories                                                             */
/* -------------------------------------------------------------------------- */

$COL_ID = "id";
$COL_NAME = "name";
$TBL_NAME = "categories";

$categories = [];

$getCatQuery = "SELECT $COL_ID, $COL_NAME FROM $TBL_NAME ORDER BY $COL_NAME ASC";
$result = $db->query($getCatQuery);
while ($row = $result->fetch_assoc()) {
  $categories[] = [
    "id" => $row[$COL_ID],
    "name" => $row[$COL_NAME]
  ];
}

/* -------------------------------------------------------------------------- */

$success = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $productId = $_POST["product-id"];
  $category = $_POST["product-category"];
  $name = $_POST["product-name"];
  $desc = $_POST["product-description"];
  $price = $_POST["product-price"];
  $stock = $_POST["product-stock"];

  $updateQuery = "
    UPDATE products
    SET
      category_id = $category,
      name = '$name',
      description = '$desc',
      price = $price,
      stock = $stock
    WHERE id = $productId
  ";
  $result = $db->query($updateQuery);
  if ($result === true) {
    // if (!empty($_FILES[])) {}

    echo "Produk berhasil diedit.";
    // header("Location: ./product-list.php");
    // exit;
    $success = $result !== false;
  } else {
    echo "Produk gagal diedit.";
  }
  // Util::prettyVarDump($db->error);
}

/* -------------------------------------------------------------------------- */

$db->close();

if ($success) {
  echo "sebelum";
  // Util::redirect("./product-list.php"); // Ga work??
  echo "sesudah";

  echo "
  <script>
    window.location.href = './product-list.php';
  </script>
  ";
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thrift | Admin - Edit product</title>
  <link rel="stylesheet" href="../styles/tw-output.css">
</head>

<body>
  <?php
  $thisFile = htmlspecialchars($_SERVER["PHP_SELF"]);

  $productName = htmlspecialchars($product["name"]);
  $productDesc = htmlspecialchars($product["description"]);
  $productName = htmlspecialchars($product["price"]);
  $productName = htmlspecialchars($product["stock"]);
  // $productName = htmlspecialchars($product["category"]);
  // $productName = htmlspecialchars($product["category"]);
  
  ?>

  <div class="w-full p-6 mx-auto max-w-screen-xs">
    <h1 class="font-bold text-4xl text-center">Edit Produk</h1>

    <form action="<?= $thisFile . "?id=$productId" ?>" method="post" enctype="multipart/form-data">
      <input type="hidden" name="product-id" value="<?= $productId ?>">

      <div class="input-field">
        <label for="product-name">Nama Produk*</label>
        <input type="text" name="product-name" id="product-name" class="input" required value="<?= $product["name"] ?>">
      </div>

      <div class="input-field">
        <label for="product-description">Deskripsi</label>
        <!-- <input type="text" name="product-description" id="product-description" class="input"> -->
        <textarea name="product-description" id="product-description" class="input"><?= $product["description"] ?></textarea>
      </div>

      <div class="input-field">
        <label for="product-price">Harga*</label>
        <input type="number" name="product-price" id="product-price" min="0" class="input" required
          value="<?= $product["price"] ?>">
      </div>

      <div class="input-field">
        <label for="product-category">Kategori*</label>
        <select name="product-category" id="product-category" class="input">
          <?php foreach ($categories as $category) { ?>
            <option value="<?= $category["id"] ?>" <?= $category["id"] === $product["category_id"] ? "selected" : null ?>>
              <?= $category["name"] ?>
            </option>
          <?php } ?>

        </select>
      </div>

      <div class=" input-field">
        <label for="product-images">Gambar</label>
        <?php if (!empty($product["image_url"])) { ?>
          <img src="../img/<?= $product["image_url"] ?>" alt="" class=" w-32">
        <?php } ?>
      </div>

      <div class="input-field">
        <label for="product-stock">Stok</label>
        <input type="number" name="product-stock" id="product-stock" min="1" class="input"
          value="<?= $product["stock"] ?>">
      </div>

      <button type="submit" class="w-full btn btn-md btn-primary">
        Perbarui
      </button>
    </form>
  </div>
</body>

</html>
