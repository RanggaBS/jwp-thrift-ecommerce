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

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thrift | Wishlist</title>
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
          <h1 class="pb-4 text-2xl font-bold">Wishlist</h1>

          <ul class="grid gap-4 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-4">
            <?php for ($i = 0; $i <= 10; $i++) { ?>
              <li>
                <a href="#" class="block border border-black rounded-lg">
                  <img src="./assets/images/kaos-1.jpg" alt="" class="rounded-lg">

                  <div class="border-t border-black">
                    <h3>Kaos <?= $i + 1 ?></h3>
                    <p class="font-semibold">Rp <?= rand(40000, 200000) ?></p>
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
