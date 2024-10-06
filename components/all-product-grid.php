<?php

/* -------------------------------------------------------------------------- */
/* Imports                                                                    */
/* -------------------------------------------------------------------------- */

require_once __DIR__ . "../../core/db.php";
require_once __DIR__ . "../../lib/utils.php";
require_once __DIR__ . "../../lib/constants.php";

/* -------------------------------------------------------------------------- */

$query = "
SELECT
	products.id, products.name, products.price, product_images.image_url
FROM
	products
	LEFT JOIN product_images ON products.id = product_images.product_id
ORDER BY
	RAND()
LIMIT 10
";
$result = $db->query($query);

?>

<ul class="grid gap-4 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">

  <?php while ($row = $result->fetch_assoc()) { ?>

    <li>
      <a href="./product.php?id=<?= $row["id"] ?>"
        class="block h-full border border-black rounded-lg hover:border-primary hover:border-2 active:scale-95 transition">
        <img src="./img/<?= $row["image_url"] ?>" alt="" class="rounded-lg aspect-square object-cover">

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
