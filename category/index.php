<?php

/* -------------------------------------------------------------------------- */
/* Imports                                                                    */
/* -------------------------------------------------------------------------- */

require_once __DIR__ . "../../core/db.php";
require_once __DIR__ . "../../lib/utils.php";
// require_once __DIR__ . "/lib/constants.php";

/* -------------------------------------------------------------------------- */

if (
  !isset($_GET["id"])
  || $_GET["id"] === ""
  || !intval($_GET["id"])
  || intval($_GET["id"]) < 0
) {
  Util::redirect("../not-found.html");
}

/* -------------------------------------------------------------------------- */

$categoryId = $_GET["id"];
$categoryProductQuery = "
  SELECT
    products.*,
    product_images.image_url
  FROM
    products
    LEFT JOIN product_images ON products.id = product_images.product_id
  WHERE
    category_id = $categoryId
  ORDER BY
    RAND()
  LIMIT 10
";
$result = $db->query($categoryProductQuery);

$categoryName = $db->query(
  "SELECT name FROM categories WHERE id = $categoryId"
)->fetch_assoc()["name"];

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thrift | Category</title>
  <link rel="stylesheet" href="../styles/tw-output.css">
</head>

<body class="min-h-dvh bg-zinc-50">
  <?php
  include_once __DIR__ . '../../components/navbar.php';
  ?>

  <main>
    <section>
      <div class="section-wrapper">
        <h1 class="text-4xl font-bold pb-6">
          <?= $categoryName ?>
        </h1>

        <ul class="grid gap-4 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">

          <?php while ($row = $result->fetch_assoc()) { ?>
            <li>
              <a href="/product.php?id=<?= $row["id"] ?>"
                class="block h-full border border-black rounded-lg hover:border-primary hover:border-2 active:scale-95 transition">
                <img src="../img/<?= $row["image_url"] ?>" alt="" class="object-cover rounded-lg aspect-square">

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
  </main>

  <?php
  include_once __DIR__ . '../../components/mobile-bottombar.php';
  ?>
</body>

</html>
