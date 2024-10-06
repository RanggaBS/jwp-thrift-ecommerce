<?php

/* -------------------------------------------------------------------------- */
/* Imports                                                                    */
/* -------------------------------------------------------------------------- */

require_once __DIR__ . "/core/db.php";
require_once __DIR__ . "/lib/utils.php";
// require_once __DIR__ . "/lib/constants.php";

/* -------------------------------------------------------------------------- */
/* Session Check                                                              */
/* -------------------------------------------------------------------------- */

if (!Util::isLoggedIn()) {
  header("Location: ./login.php");
  exit;
}

/* -------------------------------------------------------------------------- */

$isCheckedOut = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
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

        // echo "Checkout berhasil!";

        $isCheckedOut = true;

        // Util::redirect("../cart.php");
      } else {
        echo "Terjadi kesalahan: $db->error";
      }
    } else {
      // echo "Keranjangmu kosong.";
    }
  } else {
    // echo "Keranjangmu kosong.";
  }
}

/* -------------------------------------------------------------------------- */

$userId = $_SESSION["userId"];

$cartQuery = "
  SELECT id
  FROM carts
  WHERE user_id = $userId
";
$cartResult = $db->query($cartQuery);

$total = 0;

$isCartEmpty = true;

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thrift | Keranjang</title>
  <link rel="stylesheet" href="./styles/tw-output.css">
</head>

<body>
  <div id="root" class="bg-zinc-50 min-h-dvh">
    <?php
    include_once __DIR__ . '/components/navbar.php';
    ?>

    <main>
      <section>
        <div class="section-wrapper !max-w-screen-lg pb-8">
          <h1 class="pb-8 text-3xl font-bold">Keranjang</h1>

          <?php if ($isCheckedOut) { ?>
            <p class="flex items-center gap-4 px-4 py-3 mb-4 text-white rounded-md bg-lime-500">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-check">
                <path d="M20 6 9 17l-5-5" />
              </svg>

              Checkout berhasil
            </p>
          <?php } ?>

          <div class="flex flex-col justify-between gap-16 md:flex-row">
            <ul class="flex flex-col gap-8">

              <?php
              if ($cartResult->num_rows > 0) {
                $cartRow = $cartResult->fetch_assoc();
                $cartId = $cartRow["id"];

                $sql = "
                  SELECT
                    cart_products.id AS cart_product_id,
                    products.id,
                    products.name,
                    products.price,
                    cart_products.quantity,
                    product_images.image_url
                  FROM cart_products
                    JOIN products ON cart_products.product_id = products.id
                    LEFT JOIN product_images ON products.id = product_images.product_id
                  WHERE cart_products.cart_id = $cartId
                ";
                $result = $db->query($sql);

                if ($result->num_rows > 0) {
                  $isCartEmpty = false;

                  $total = 0;
                  $index = 0;
                  while ($row = $result->fetch_assoc()) {
                    $subTotal = $row["price"] * $row["quantity"];
                    $total += $subTotal; ?>

                    <!-- Separator -->
                    <?php if ($index !== 0) { ?>
                      <hr class="my-4 border-black">
                    <?php } ?>

                    <li>
                      <div class="flex flex-col gap-6 sm:flex-row">
                        <div class="bg-zinc-950 max-h-64">
                          <a href="./product.php?id=<?= $row["id"] ?>" class="">
                            <img src="./img/<?= $row["image_url"] ?>" alt="" class="object-cover h-full mx-auto max-h-64">
                          </a>
                        </div>

                        <div class="w-full p-4">
                          <h2 class="text-lg">
                            <?= $row["name"] ?>
                          </h2>

                          <p class="pb-6 text-xl font-semibold">
                            Rp <?= Util::formatRupiahTanpaKoma($row["price"]) ?>
                          </p>

                          <div class="flex items-center gap-2 w-max">
                            <form action="./api/product-decrement-quantity.php" method="post">
                              <input type="hidden" name="cartProductId" value="<?= $row["cart_product_id"] ?>">
                              <button type="submit" class="btn btn-icon btn-outline">
                                -
                              </button>
                            </form>

                            <?php /* $nameAndId = "product-" . ($i + 1) . "-quantity"; */ ?>

                            <input type="number" name="<?php /* $nameAndId */ ?>" id="<?php /* $nameAndId */ ?>" min="0"
                              value="<?= $row["quantity"] ?>" class="flex-grow-0 flex-shrink text-center max-w-16 input">

                            <form action="./api/product-increment-quantity.php" method="post">
                              <input type="hidden" name="cartProductId" value="<?= $row["cart_product_id"] ?>">
                              <button type="submit" class="btn btn-icon btn-outline">
                                +
                              </button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </li>

                    <?php $index++ ?>
                  <?php } ?>

              <?php
                } else {
                  echo "Keranjangmu kosong. Yuk mulai belanja sekarang.";
                }
              } else {
                echo "Keranjangmu kosong. Yuk mulai belanja sekarang.";
              }
              ?>
            </ul>

            <?php if (!$isCartEmpty) { ?>

              <div class="w-full px-6 py-5 space-y-4 border border-black rounded-lg md:max-w-64">
                <div class="flex items-center justify-between gap-6 text-lg font-bold">
                  <p class="">Total</p>
                  <p>
                    Rp <?= Util::formatRupiahTanpaKoma($total) ?>
                  </p>
                </div>

                <?php $thisFile = htmlspecialchars($_SERVER["PHP_SELF"]); ?>
                <form action="<?= $thisFile ?>" method="post">
                  <button type="submit" class="w-full btn btn-md btn-primary">
                    Checkout
                  </button>
                </form>
              </div>

            <?php } ?>

          </div>
        </div>
      </section>
    </main>

    <?php
    include_once __DIR__ . '/components/mobile-bottombar.php';
    ?>
  </div>
</body>

</html>
