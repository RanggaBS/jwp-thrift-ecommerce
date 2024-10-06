<?php

/* -------------------------------------------------------------------------- */
/* Imports                                                                    */
/* -------------------------------------------------------------------------- */

require_once __DIR__ . "/core/db.php";
require_once __DIR__ . "/lib/utils.php";
// require_once __DIR__ . "/lib/constants.php";

$isAddedToCart = false;

if ($_SERVER["REQUEST_METHOD"] === "POST" && !Util::isLoggedIn()) {
  Util::redirect("/login.php");
}

// Surpress notice message "session already started"
if (@Util::isLoggedIn()) {


  /* -------------------------------------------------------------------------- */
  /* Get user's ID from session                                                 */
  /* -------------------------------------------------------------------------- */

  $userId = $_SESSION["userId"];

  /* -------------------------------------------------------------------------- */
  /* Get or create cart for user                                                */
  /* -------------------------------------------------------------------------- */

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
  /* Add product to cart or increment the quantity by 1                         */
  /* -------------------------------------------------------------------------- */

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $productId = $_POST["productId"];

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

    $isAddedToCart = true;
  }


  /* -------------------------------------------------------------------------- */
  /* Redirect if category not found                                             */
  /* -------------------------------------------------------------------------- */

  if (
    $_SERVER["REQUEST_METHOD"] !== "POST"
    && (
      !isset($_GET["id"])
      || $_GET["id"] === ""
      || !intval($_GET["id"])
      || intval($_GET["id"]) < 0
    )
  ) {
    header("Location: ./not-found.html");
    exit;
  }
}

/* -------------------------------------------------------------------------- */
/* Get product detail                                                         */
/* -------------------------------------------------------------------------- */

$productId = $_GET["id"];

$query = "
  SELECT
    products.id,
    products.category_id,
    products.name,
    products.description,
    products.price,
    product_images.image_url
  FROM
    products
    LEFT JOIN product_images ON products.id = product_images.product_id
  WHERE
    products.id = $productId
";
$product = $db->query($query)->fetch_assoc();


?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thrift | Product</title>
  <link rel="stylesheet" href="./styles/tw-output.css">
</head>

<body>
  <div id="root" class="bg-zinc-50 min-h-dvh">
    <?php
    include_once __DIR__ . '/components/navbar.php';
    ?>

    <main>
      <section>
        <div class="section-wrapper">
          <!-- <a href="../" class="inline-block pb-6 link">&larr; Kembali</a> -->

          <h1 class="pb-6 text-4xl font-bold">Produk</h1>

          <?php if ($isAddedToCart) { ?>
            <p class="flex items-center gap-4 px-4 py-3 mb-4 text-white rounded-md bg-lime-500">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-check">
                <path d="M20 6 9 17l-5-5" />
              </svg>

              Berhasil ditambahkan ke keranjang
            </p>
          <?php } ?>

          <article class="flex flex-col sm:flex-row">
            <div class="w-full bg-zinc-950 sm:max-w-lg max-h-80">
              <img src="./img/<?= $product["image_url"] ?>" alt=""
                class="mx-auto max-w-64 h-full aspect-square  object-cover">
            </div>

            <div class="flex flex-col justify-between p-4">
              <div class="pb-6">
                <h2>
                  <?= $product["name"] ?>
                </h2>

                <p class="text-lg font-medium">
                  Rp <?= Util::formatRupiahTanpaKoma($product["price"]) ?>
                </p>

                <p class="pt-2">
                  <?= $product["description"] ?>
                </p>
              </div>

              <?php
              $thisFile = htmlspecialchars($_SERVER["PHP_SELF"]);
              ?>

              <form action="<?= $thisFile . "?id=" . $productId ?>" method="post">
                <input type="hidden" name="productId" value="<?= $product["id"] ?>">
                <button type="submit" class="flex items-center justify-center w-full gap-4 btn btn-md btn-primary">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-shopping-cart">
                    <circle cx="8" cy="21" r="1" />
                    <circle cx="19" cy="21" r="1" />
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                  </svg>

                  Tambahkan
                </button>
              </form>
            </div>
          </article>
        </div>
      </section>

      <?php
      $productId = $product["id"];
      $productCat = $product["category_id"];
      $productName = $product["name"];
      $productDesc = $product["description"];
      $otherProdQuery = "SELECT
          products.id,
          products.name,
          products.description,
          products.price,
          product_images.image_url
        FROM
          products
          LEFT JOIN product_images ON products.id = product_images.product_id
        WHERE
          products.id != $productId
          AND products.category_id = $productCat
          AND (
            products.name LIKE '%$productName%'
            OR products.description LIKE '%$productDesc%'
          )
        ORDER BY
          RAND()
        LIMIT 5
      ";
      $result = $db->query($otherProdQuery);
      if ($result->num_rows > 0) {
      ?>
        <section>
          <div class="py-6 section-wrapper">
            <h2 class="pt-8 pb-4 text-xl font-bold">Produk Lainnya</h2>

            <ul class="grid gap-4 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">

              <?php while ($row = $result->fetch_assoc()) { ?>

                <li>
                  <a href="/product.php?id=<?= $row["id"] ?>"
                    class="block h-full transition border border-black rounded-lg hover:border-primary hover:border-2 active:scale-95">
                    <img src="./img/<?= $row["image_url"] ?>" alt="" class="object-cover rounded-lg aspect-square">

                    <div class="px-4 py-3 border-t border-black">
                      <h3>
                        <?= $row["name"] ?>
                      </h3>

                      <p class="font-semibold">
                        Rp <?= Util::formatRupiahTanpaKoma($row["price"]) ?>
                      </p>
                    </div>
                  </a>
                </li>

              <?php } ?>

            </ul>
          </div>
        </section>

      <?php } ?>
    </main>

    <!-- An empty div with the height same as mobile bottom navbar -->
    <div class="h-6 2xs:h-10 xs:h-12"></div>

    <?php
    include_once __DIR__ . '/components/mobile-bottombar.php';
    ?>
  </div>
</body>

</html>

<?php

/* -------------------------------------------------------------------------- */
/* Close connection                                                           */
/* -------------------------------------------------------------------------- */

$db->close();
?>
