<?php

/* -------------------------------------------------------------------------- */
/* Imports                                                                    */
/* -------------------------------------------------------------------------- */

require_once __DIR__ . "../../core/db.php";
require_once __DIR__ . "../../lib/utils.php";
// require_once __DIR__ . "/lib/constants.php";

$result = $db->query("SELECT * FROM categories ORDER BY name ASC");

?>

<div id="swiper-category" class="swiper">
  <ul class="swiper-wrapper">
    <?php while ($row = $result->fetch_assoc()) { ?>
      <li class="!w-max swiper-slide">
        <a href="./category/index.php?id=<?= $row["id"] ?>" class="category-badge">
          <?= $row["name"] ?>
        </a>
      </li>

    <?php } ?>

  </ul>
</div>

<script src="../lib/swiper/swiper-bundle.min.js"></script>
<script>
  const swiperCategory = new Swiper("#swiper-category", {
    slidesPerView: "auto",
    spaceBetween: 16,
    speed: 500,
    autoplay: {
      enabled: true,
      delay: 5000,
    }
  })
</script>
