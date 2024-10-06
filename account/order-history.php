<?php

/* -------------------------------------------------------------------------- */
/* Imports                                                                    */
/* -------------------------------------------------------------------------- */

require_once __DIR__ . "../../core/db.php";
require_once __DIR__ . "../../lib/utils.php";
// require_once __DIR__ . "/lib/constants.php";

/* -------------------------------------------------------------------------- */
/* Session Check                                                              */
/* -------------------------------------------------------------------------- */

if (!Util::isLoggedIn()) {
  header("Location: ./login.php");
  exit;
}

/* -------------------------------------------------------------------------- */

$userId = $_SESSION["userId"];

$orderQuery = "
  SELECT
    *
  FROM
    orders
  WHERE
    user_id = $userId
  ORDER BY created_at DESC
";
$orderResult = $db->query($orderQuery);

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thrift | Riwayat pesananmu</title>
  <link rel="stylesheet" href="../styles/tw-output.css">
</head>

<body class="bg-zinc-50 min-h-dvh">
  <?php
  include_once __DIR__ . '../../components/navbar-without-search.php';
  ?>

  <main>
    <section>
      <div class="section-wrapper">
        <a href="./" class="btn btn-md btn-primary inline-block mb-4">&larr; Kembali</a>

        <h1 class="font-bold text-4xl py-4">Riwayat pesanan</h1>

        <?php if ($orderResult->num_rows <= 0) { ?>
          <p>Kamu belum memesan apapun.</p>
        <?php } else { ?>
          <ul class="flex flex-col gap-8">
            <?php while ($row = $orderResult->fetch_assoc()) { ?>
              <li class="border border-black">
                <div>
                  <div class="p-4">
                    <p class="font-bold text-lg">Total: Rp <?= Util::formatRupiahTanpaKoma($row["total_price"]) ?></p>
                    <p>Order ID: <?= $row["id"] ?></p>
                    <p>Tanggal: <?= $row["created_at"] ?></p>
                  </div>

                  <?php
                  $orderId = $row["id"];
                  $orderProductQuery = "
                SELECT
                  products.*,
                  order_products.*,
                  product_images.image_url
                FROM
                  order_products
                JOIN products ON order_products.product_id = products.id
                LEFT JOIN product_images ON products.id = product_images.product_id
                WHERE order_products.order_id = $orderId
              ";
                  $orderProductResult = $db->query($orderProductQuery);
                  ?>

                  <style>
                    th,
                    td {
                      border: 1px solid black;
                      border-collapse: collapse;
                    }
                  </style>

                  <table class="mt-4 w-full border-collapse overflow-x-auto" cellpadding="12">
                    <tr>
                      <th>Gambar</th>
                      <th>Nama</th>
                      <th>Kuantitas</th>
                      <th>Harga</th>
                      <th>Subtotal</th>
                    </tr>
                    <?php while ($row = $orderProductResult->fetch_assoc()) { ?>
                      <tr>
                        <td>
                          <img src="../img/<?= $row["image_url"] ?>" alt="" class="w-48">
                        </td>
                        <td>
                          <?= htmlspecialchars($row["name"]) ?>
                        </td>
                        <td>
                          <?= htmlspecialchars($row["quantity"]) ?>
                        </td>
                        <td>
                          Rp <?= number_format($row["price"], 2) ?>
                        </td>
                        <td>
                          Rp <?= number_format($row["quantity"] * $row["price"], 2) ?>
                        </td>
                      </tr>
                    <?php } ?>
                    <?php ?>
                  </table>
                </div>
              </li>
            <?php } ?>
          </ul>
        <?php } ?>
      </div>
    </section>
  </main>

  <?php
  include_once __DIR__ . '../../components/mobile-bottombar.php';
  ?>
</body>

</html>
