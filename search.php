<?php

/* -------------------------------------------------------------------------- */
/* Imports                                                                    */
/* -------------------------------------------------------------------------- */

require_once __DIR__ . "/core/db.php";
require_once __DIR__ . "/lib/utils.php";
// require_once __DIR__ . "/lib/constants.php";

$searchQuery = '';

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["search"])) {
  $keyword = $_GET["search"];

  $searchQuery = "
    SELECT
      products.id,
      products.name,
      products.price,
      product_images.image_url
    FROM
      products
      LEFT JOIN
        product_images ON products.id = product_images.product_id
    WHERE
      name LIKE '%$keyword%'
      OR description LIKE '%$keyword%'
  ";
} else {
  $searchQuery = "
    SELECT
      products.id,
      products.name,
      products.price,
      product_images.image_url
    FROM
      products
    LEFT JOIN
      product_images ON products.id = product_images.product_id
    ORDER BY
      RAND ()
  ";
}

$result = $db->query($searchQuery);

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thrift | Search</title>
  <link rel="stylesheet" href="./styles/tw-output.css">
</head>

<body>
  <div id="root" class="bg-zinc-50 min-h-dvh">
    <?php
    include_once __DIR__ . '/components/navbar-without-search.php';
    ?>

    <main>
      <section>
        <div class="section-wrapper">
          <h2 class="pb-4 text-2xl font-bold">Pencarian</h2>

          <?php
          include_once __DIR__ . '/components/search-without-handler.php';
          ?>

          <?php
          if (isset($keyword) && strlen($keyword) > 0) { ?>
            <p class="pt-4">
              Hasil pencarian untuk: "<?= $keyword ?>"
            </p>
          <?php } else { ?>
            <p class="pt-4 text-xl">
              Semua produk:
            </p>
          <?php } ?>

          <ul class="grid gap-4 pt-6 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
            <?php while ($row = $result->fetch_assoc()) { ?>
              <li>
                <a href="./product.php?id=<?= $row["id"] ?>" class="block h-full border border-black rounded-lg">
                  <img src="./img/<?= $row["image_url"] ?>" alt="" class="object-cover rounded-t-lg aspect-square">

                  <div class="px-4 py-3 border-t border-black">
                    <h3>Kaos <?= $row["id"] ?></h3>
                    <p class="font-semibold">Rp <?= Util::formatRupiahTanpaKoma($row["price"]) ?></p>
                  </div>
                </a>
              </li>
            <?php } ?>
          </ul>
        </div>
      </section>
    </main>

    <?php
    include_once __DIR__ . '/components/mobile-bottombar.php';
    ?>

  </div>
</body>

</html>
